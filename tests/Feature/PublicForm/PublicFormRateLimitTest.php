<?php

namespace Tests\Feature\PublicForm;

use App\Http\Middleware\SanitizeInputMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicFormRateLimitTest extends TestCase
{
    /** @test */
    public function public_form_routes_use_separate_named_limiters(): void
    {
        $show = Route::getRoutes()->getByName('public.form.show');
        $submit = Route::getRoutes()->getByName('public.form.submit');

        $this->assertContains('throttle:public-form-view', $show->gatherMiddleware());
        $this->assertContains('throttle:public-form-submit', $submit->gatherMiddleware());
    }

    /** @test */
    public function submit_buckets_are_isolated_by_token_and_from_view_buckets(): void
    {
        config([
            'public_form.rate_limits.submit_per_ip' => 100,
            'public_form.rate_limits.submit_per_token' => 1,
            'public_form.rate_limits.view_per_ip' => 100,
            'public_form.rate_limits.view_per_token' => 100,
        ]);
        RateLimiter::clear('unused');
        Route::post('/_security-test/submit/{token}', fn () => response('ok'))
            ->middleware('throttle:public-form-submit');
        Route::get('/_security-test/view/{token}', fn () => response('ok'))
            ->middleware('throttle:public-form-view');
        $this->withoutMiddleware([SanitizeInputMiddleware::class, VerifyCsrfToken::class]);

        $this->post('/_security-test/submit/token-a')->assertOk();
        $this->post('/_security-test/submit/token-a')->assertStatus(429);
        $this->post('/_security-test/submit/token-b')->assertOk();
        $this->get('/_security-test/view/token-a')->assertOk();
    }
}
