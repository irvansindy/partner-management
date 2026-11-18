<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{DB, Schema, Storage};
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email');
            $table->string('password'); $table->rememberToken(); $table->timestamps();
        });
        (require database_path('migrations/2026_11_18_000001_add_avatar_path_to_users_table.php'))->up();
        Storage::fake('local');
    }

    private function account(string $name): User
    {
        return User::create(['name' => $name, 'email' => $name.'@example.test', 'password' => 'unchanged']);
    }

    public function test_photo_updates_only_own_account_and_replacement_removes_old_file(): void
    {
        $owner = $this->account('owner');
        $other = $this->account('other');
        $this->actingAs($owner)->post(route('profile.photo.update'), [
            'id' => $other->id, 'avatar' => UploadedFile::fake()->image('photo.png', 100, 100),
        ])->assertRedirect(route('profile'))->assertSessionHas('photo_updated');
        $first = $owner->fresh()->avatar_path;
        Storage::disk('local')->assertExists($first);
        $this->assertNull($other->fresh()->avatar_path);
        $this->assertSame('unchanged', $owner->fresh()->password);
        $this->assertStringContainsString('/profile/photo?v=', $owner->fresh()->avatar_url);
        $this->get(route('profile.photo'))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->post(route('profile.photo.update'), ['avatar' => UploadedFile::fake()->image('new.jpg', 80, 80)])
            ->assertRedirect(route('profile'));
        $second = $owner->fresh()->avatar_path;
        $this->assertNotSame($first, $second);
        Storage::disk('local')->assertMissing($first);
        Storage::disk('local')->assertExists($second);
        $this->actingAs($other)->get(route('profile.photo', ['id' => $owner->id]))->assertNotFound();
    }

    public function test_invalid_files_preserve_existing_photo(): void
    {
        $owner = $this->account('owner');
        $path = 'profile-photos/'.$owner->id.'/existing.png';
        Storage::disk('local')->put($path, 'existing photo');
        $owner->update(['avatar_path' => $path]);
        foreach ([
            UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
            UploadedFile::fake()->image('big.png', 100, 100)->size(2049),
            UploadedFile::fake()->image('wide.png', 2049, 1),
            UploadedFile::fake()->createWithContent('unsafe.svg', '<svg xmlns="http://www.w3.org/2000/svg"/>'),
        ] as $file) {
            $this->actingAs($owner)->postJson(route('profile.photo.update'), ['avatar' => $file])
                ->assertStatus(422)->assertJsonValidationErrors('avatar');
            $this->assertSame($path, $owner->fresh()->avatar_path);
            Storage::disk('local')->assertExists($path);
            $this->assertCount(1, Storage::disk('local')->allFiles('profile-photos'));
        }
    }

    public function test_guest_cannot_read_or_update_photo(): void
    {
        $this->getJson(route('profile.photo'))->assertUnauthorized();
        $this->postJson(route('profile.photo.update'), [])->assertUnauthorized();
    }
}
