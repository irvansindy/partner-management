<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Services\MenuService;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Observers\GenericModelObserver;
// Models yang mau di-observe
use App\Models\CompanyInformation;
use App\Models\CompanyBank;
use App\Models\CompanyAddress;
use App\Models\CompanyTax;
use App\Models\CompanySupportingDocument;
use App\Models\UserBalanceSheet;
use App\Models\UserFinancialRatio;
use App\Models\UserValueIncomeStatement;
use App\Models\ApprovalMaster;
use App\Models\ApprovalDetails;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $dashboardUrl = $user->hasAnyRole(['super-admin', 'admin', 'super-user'])
                ? 'dashboard'
                : 'home';

            Config::set('adminlte.dashboard_url', $dashboardUrl);
        }

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        Schema::defaultStringLength(191);

        $models = [
            CompanyInformation::class,
            CompanyBank::class,
            CompanyAddress::class,
            CompanyTax::class,
            CompanySupportingDocument::class,
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