<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiPartnerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fetch-data-partner', [ApiPartnerController::class, 'fetchPartner'])->name('fetch-data-partner');


// Versi tanpa token, tapi dengan IP whitelist dari database
Route::middleware(['api.ip_whitelist'])->prefix('v1')->group(function () {
    Route::get('/company-information/public', [ApiPartnerController::class, 'fetchPartner']);
});

Route::post('blacklist-partner', [ApiPartnerController::class, 'blacklistPartner']);
