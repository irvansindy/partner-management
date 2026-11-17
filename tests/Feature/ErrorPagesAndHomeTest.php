<?php

namespace Tests\Feature;

use App\Exceptions\Handler;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ErrorPagesAndHomeTest extends TestCase
{
    public function test_home_redirects_logged_in_users_to_dashboard_without_legacy_role_gate(): void
    {
        $user = (new User())->forceFill(['id' => 1, 'name' => 'User']);
        $this->actingAs($user)->get('/home')->assertRedirect(route('dashboard'));
        $this->assertSame('/dashboard', RouteServiceProvider::HOME);
    }

    public function test_error_pages_show_localized_guidance_and_preserve_status_and_headers(): void
    {
        config(['app.debug' => false]);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ([401, 403, 404, 405, 408, 413, 419, 422, 500, 502, 503, 504] as $code) {
                $response = app(Handler::class)->render(Request::create('/missing'), new HttpException($code, 'private error details', null, ['Retry-After' => '30']));
                $this->assertSame($code, $response->getStatusCode());
                $this->assertSame('30', $response->headers->get('Retry-After'));
                $this->assertStringContainsString(__('error_pages.'.$code.'.title'), $response->getContent());
                $this->assertStringNotContainsString('private error details', $response->getContent());
            }
        }
    }

    public function test_ajax_errors_still_return_json(): void
    {
        config(['app.debug' => false]);
        $request = Request::create('/missing', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = app(Handler::class)->render($request, new HttpException(404));
        $this->assertSame(404, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
    }
}
