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
use App\Observers\GenericModelObserver;
// Models yang mau di-observe
use App\Models\CompanyInformation;
use App\Models\CompanyBank;
use App\Models\CompanyAddress;
use App\Models\CompanyTax;
use App\Models\CompanySupportingDocument;
use App\Models\User;
use App\Models\UserBalanceSheet;
use App\Models\UserFinancialRatio;
use App\Models\UserValueIncomeStatement;
use App\Models\ApprovalMaster;
use App\Models\ApprovalDetails;
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

        $models = [
            CompanyInformation::class,
            CompanyBank::class,
            CompanyAddress::class,
            CompanyTax::class,
            CompanySupportingDocument::class,
            User::class,
            UserBalanceSheet::class,
            UserFinancialRatio::class,
            UserValueIncomeStatement::class,
            ApprovalMaster::class,
            ApprovalDetails::class,
        ];

        foreach ($models as $model) {
            $model::observe(GenericModelObserver::class);
        }

    }
}
