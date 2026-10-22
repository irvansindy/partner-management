<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            // return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
            return Limit::perHour(1000)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('public-form-view', function (Request $request) {
            return $this->publicFormLimits($request, 'view');
        });

        RateLimiter::for('public-form-submit', function (Request $request) {
            return $this->publicFormLimits($request, 'submit');
        });
    }

    private function publicFormLimits(Request $request, string $action): array
    {
        $key = (string) config('app.key');
        $tokenHash = hash_hmac('sha256', (string) $request->route('token'), $key);
        $ipHash = hash_hmac('sha256', (string) $request->ip(), $key);

        return [
            Limit::perMinute((int) config("public_form.rate_limits.{$action}_per_ip"))
                ->by("public-form:{$action}:ip:{$ipHash}"),
            Limit::perMinute((int) config("public_form.rate_limits.{$action}_per_token"))
                ->by("public-form:{$action}:token:{$tokenHash}"),
        ];
    }
}
