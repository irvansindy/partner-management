<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Services\MenuService;
use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $dashboardUrl = $user->hasAnyRole(['super-admin', 'admin', 'super-user'])
                ? 'dashboard'
                : 'home';

            // Replace config value (overwrite closure)
            Config::set('adminlte.dashboard_url', $dashboardUrl);
        }

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        Schema::defaultStringLength(191);
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $menuService = new MenuService();
                $view->with('menus', $menuService->getAccessibleMenus());
            }
        });

    }
}
