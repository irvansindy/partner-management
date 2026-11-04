<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->get('/_test/active-locale', function () {
            return response()->json([
                'app' => app()->getLocale(),
                'carbon' => Carbon::getLocale(),
            ]);
        });
    }

    /** @test */
    public function it_persists_a_supported_locale_and_returns_to_the_previous_url(): void
    {
        $response = $this->from('/_test/active-locale')->get('/lang/en');

        $response->assertRedirect('/_test/active-locale');
        $this->assertSame('en', session('locale'));
        $this->get('/_test/active-locale')->assertExactJson(['app' => 'en', 'carbon' => 'en']);
    }

    /** @test */
    public function it_rejects_an_unsupported_locale(): void
    {
        $this->get('/lang/fr')->assertStatus(400);
    }

    /** @test */
    public function it_does_not_redirect_to_an_external_referer(): void
    {
        foreach (['https://attacker.example/phishing', 'javascript:alert(1)', 'https:evil.example/path', '//attacker.example'] as $referer) {
            $this->withHeader('referer', $referer)
                ->get('/lang/en')
                ->assertRedirect('/');
        }
    }

    /** @test */
    public function it_returns_to_the_same_host_when_the_public_port_differs(): void
    {
        $this->withHeader('referer', 'http://localhost:8084/f/public-token?step=2')
            ->get('/lang/en')
            ->assertRedirect('/f/public-token?step=2');
    }

    /** @test */
    public function it_uses_an_explicit_internal_return_path_without_a_referer(): void
    {
        $this->get('/lang/en?return_to=' . urlencode('/f/public-token?step=3'))
            ->assertRedirect('/f/public-token?step=3');

        $this->get('/lang/id?return_to=' . urlencode('//attacker.example/phishing'))
            ->assertRedirect('/');
    }

    /** @test */
    public function it_falls_back_for_a_forged_session_locale(): void
    {
        $this->withSession(['locale' => 'invalid-locale'])
            ->get('/_test/active-locale')
            ->assertExactJson(['app' => 'id', 'carbon' => 'id']);
    }
}
