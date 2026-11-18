<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Hash, Schema};
use Tests\TestCase;

class ProfilePasswordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    private function account(string $name): User
    {
        return User::create(['name' => $name, 'email' => $name.'@example.test',
            'password' => Hash::make('Old<&Password123!'), 'remember_token' => 'old-token']);
    }

    public function test_password_changes_only_for_logged_in_user_even_with_tampered_id(): void
    {
        $user = $this->account('owner');
        $other = $this->account('other');
        $otherHash = $other->password;
        $this->actingAs($user)->post(route('profile.password.update'), [
            'id' => $other->id, 'current_password' => 'Old<&Password123!',
            'password' => 'New<&Password456!', 'password_confirmation' => 'New<&Password456!',
        ])->assertRedirect(route('profile'))->assertSessionHas('password_updated');
        $this->assertTrue(Hash::check('New<&Password456!', $user->fresh()->password));
        $this->assertFalse(Hash::check('Old<&Password123!', $user->fresh()->password));
        $this->assertNotSame('old-token', $user->fresh()->remember_token);
        $this->assertSame($otherHash, $other->fresh()->password);
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_current_password_new_password_and_confirmation_leave_hash_unchanged(): void
    {
        $user = $this->account('owner');
        $hash = $user->password;
        foreach ([
            ['wrong', 'NewPassword123!', 'NewPassword123!', 'current_password'],
            ['Old<&Password123!', 'short', 'short', 'password'],
            ['Old<&Password123!', 'NewPassword123!', 'mismatch', 'password'],
            ['Old<&Password123!', 'Old<&Password123!', 'Old<&Password123!', 'password'],
        ] as [$current, $new, $confirmation, $field]) {
            $this->actingAs($user)->postJson(route('profile.password.update'), [
                'current_password' => $current, 'password' => $new, 'password_confirmation' => $confirmation,
            ])->assertStatus(422)->assertJsonValidationErrors($field);
            $this->assertSame($hash, $user->fresh()->password);
        }
    }

    public function test_passwords_are_never_flashed_on_validation_failure(): void
    {
        $user = $this->account('owner');
        $this->actingAs($user)->from('/profile')->post(route('profile.password.update'), [
            'current_password' => 'wrong', 'password' => 'NewPassword123!', 'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect('/profile')->assertSessionHasErrors('current_password', null, 'passwordUpdate');
        foreach (['current_password', 'password', 'password_confirmation'] as $field) {
            $this->assertNull(session()->getOldInput($field));
        }
    }

    public function test_guest_cannot_change_a_password(): void
    {
        $this->postJson(route('profile.password.update'), [])->assertUnauthorized();
    }
}
