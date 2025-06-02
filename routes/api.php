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
// Tambahkan dokumentasi API di URL /docs/api
// Route::middleware('auth')->group(function () {
//     Scramble::routes(function () {
//         // Tidak perlu isi apapun jika tidak ada konfigurasi khusus
//     });
// });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('fetch-data-partner', [ApiPartnerController::class, 'fetchPartner'])->name('fetch-data-partner');

// Versi tanpa token, tapi dengan IP whitelist dari database
Route::middleware(['api.ip_whitelist'])->prefix('v1')->group(function () {
    Route::get('/company-information/public', [ApiPartnerController::class, 'fetchPartner']);
});

Route::post('blacklist-partner', [ApiPartnerController::class, 'blacklistPartner']);

Scramble::routes(function ($route) {
    return in_array($route->getActionMethod(), ['fetchPartner', 'blacklistPartner']);
});