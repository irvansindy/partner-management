<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserPasswordUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        (require database_path('migrations/2024_03_05_131125_create_permission_tables.php'))->up();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    private function account(string $role = 'super-admin'): User
    {
        $user = User::create(['name' => 'Test', 'email' => uniqid().'@example.test', 'password' => Hash::make('old-password'), 'remember_token' => 'old-token']);
        $user->assignRole(Role::findOrCreate($role));
        return $user;
    }

    public function test_super_admin_changes_only_target_password_and_rotates_remember_token(): void
    {
        $actor = $this->account();
        $target = $this->account('admin');
        $password = 'New<&Password123!';
        $this->actingAs($actor)->postJson(route('update-user-password'), [
            'id' => $target->id, 'password' => $password, 'password_confirmation' => $password,
        ])->assertOk()->assertJsonPath('data', null);
        $this->assertTrue(Hash::check($password, $target->fresh()->password));
        $this->assertNotSame('old-token', $target->fresh()->remember_token);
        $this->assertTrue(Hash::check('old-password', $actor->fresh()->password));
        $this->assertSame('Test', $target->fresh()->name);
        $this->assertTrue($target->fresh()->hasRole('admin'));
    }

    public function test_invalid_password_and_confirmation_preserve_existing_hash(): void
    {
        $actor = $this->account();
        $hash = $actor->password;
        foreach ([['short', 'short'], ['new-password', 'mismatch']] as [$password, $confirmation]) {
            $this->actingAs($actor)->postJson(route('update-user-password'), [
                'id' => $actor->id, 'password' => $password, 'password_confirmation' => $confirmation,
            ])->assertStatus(422)->assertJsonValidationErrors('password');
            $this->assertSame($hash, $actor->fresh()->password);
        }
    }

    public function test_guest_and_non_super_admin_cannot_change_passwords(): void
    {
        $target = $this->account('admin');
        $data = ['id' => $target->id, 'password' => 'new-password', 'password_confirmation' => 'new-password'];
        $this->postJson(route('update-user-password'), $data)->assertUnauthorized();
        $this->actingAs($target)->postJson(route('update-user-password'), $data)->assertForbidden();
        $this->assertTrue(Hash::check('old-password', $target->fresh()->password));
    }

    public function test_success_messages_follow_active_language(): void
    {
        $actor = $this->account();
        foreach (['id', 'en'] as $locale) {
            $this->actingAs($actor)->withSession(['locale' => $locale])->postJson(route('update-user-password'), [
                'id' => $actor->id, 'password' => 'new-password', 'password_confirmation' => 'new-password',
            ])->assertOk()->assertJsonPath('meta.message', trans('user_password.success', [], $locale));
        }
    }
}
