<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiPartnerController;
use Dedoc\Scramble\Scramble;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Versi tanpa token, tapi dengan IP whitelist dari database
Route::middleware(['api.ip_whitelist'])->prefix('v1')->group(function () {
    Route::get('/company-information/public', [ApiPartnerController::class, 'fetchPartner']);
    Route::get('/company-information/vendor/public', [ApiPartnerController::class, 'fetchVendorForTender']);
});

Route::post('blacklist-partner', [ApiPartnerController::class, 'blacklistPartner']);

Scramble::routes(function ($route) {
    return in_array($route->getActionMethod(), ['fetchPartner', 'fetchVendorForTender', 'blacklistPartner']);
});