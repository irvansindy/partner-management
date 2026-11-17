<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MenuService;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    public function test_profile_requires_login(): void
    {
        $this->get('/profile')->assertRedirect(route('login'));
    }

    public function test_profile_shows_only_authenticated_account_in_both_languages(): void
    {
        $this->mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        });
        $user = (new User())->forceFill([
            'id' => 15, 'name' => 'My Account', 'email' => 'my-account@example.test',
            'password' => 'hidden-password-value',
        ]);
        $user->setRelation('roles', collect([(object) ['name' => 'admin']]));
        foreach (['office', 'division', 'dept', 'jobTitle'] as $relation) {
            $user->setRelation($relation, null);
        }
        foreach (['id', 'en'] as $locale) {
            $this->actingAs($user)->withSession(['locale' => $locale])
                ->get('/profile?id=999')
                ->assertOk()
                ->assertViewHas('user', fn ($profile) => $profile->id === 15)
                ->assertSee('my-account@example.test')
                ->assertSee(trans('profile.title', [], $locale))
                ->assertDontSee('hidden-password-value');
        }
    }
}
