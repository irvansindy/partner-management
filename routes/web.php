<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PartnerManagementController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ApprovalSettingController;

use App\Http\Controllers\API\ApiPartnerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// for php artisan route cache
// for php artisan optimize clear
// Route::get('', function () {});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:user'])->group(function () {
    // Route::get('/partner', [PartnerController::class,'index'])->name('partner');
    Route::get('/create-partner', [PartnerController::class,'viewCreatePartner'])->name('create-partner');
    Route::get('/fetch-doctype', [PartnerController::class,'fetchDocTypeCategories'])->name('fetch-doctype');
    Route::get('/fetch-partner', [PartnerController::class,'fetchCompany'])->name('fetch-partner');
    Route::get('/detail-partner', [PartnerController::class,'detailPartner'])->name('detail-partner');
    Route::get('/fetch-partner-byuser', [PartnerController::class,'fetchCompanyPartnerById'])->name('fetch-partner-byuser');
    Route::post('/submit-partner', [PartnerController::class,'store'])->name('submit-partner');
    Route::post('/update-partner', [PartnerController::class,'update'])->name('update-partner');
    Route::post('store-attachment', [PartnerController::class,'storeAttachment'])->name('store-attachment');
    Route::post('update-attachment', [PartnerController::class,'updateAttachmentById'])->name('update-attachment');
});

Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/user-manage', [UserManagementController::class,'index'])->name('user-manage');
    Route::get('/fetch-user', [UserManagementController::class,'fetchUser'])->name('fetch-user');
    Route::get('/fetch-role-office-dept', [UserManagementController::class,'fetchRoleOfficeDepartment'])->name('fetch-role-office-dept');
    Route::post('/store-user', [UserManagementController::class,'storeUser'])->name('store-user');
    Route::get('/detail-user', [UserManagementController::class,'detailUser'])->name('detail-user');
    Route::post('/update-user', [UserManagementController::class,'updateUser'])->name('update-user');
    Route::post('/delete-user', [UserManagementController::class,'deleteUser'])->name('delete-user');
    
    Route::get('/role-permission', [RoleAndPermissionController::class,'index'])->name('role-permission');
    Route::get('/fetch-role', [RoleAndPermissionController::class,'fetchRole'])->name('fetch-role');
    Route::get('/fetch-permission', [RoleAndPermissionController::class,'fetchPermission'])->name('fetch-permission');
    
    Route::post('store-role', [RoleAndPermissionController::class,'storeRole'])->name('store-role');
    Route::get('detail-role', [RoleAndPermissionController::class,'detailRole'])->name('detail-role');
    Route::post('update-role', [RoleAndPermissionController::class,'updateRole'])->name('update-role');
    Route::post('delete-role', [RoleAndPermissionController::class,'deleteRole'])->name('delete-role');
    
    Route::post('store-permission', [RoleAndPermissionController::class,'storePermission'])->name('store-permission');
    Route::get('detail-permission', [RoleAndPermissionController::class,'detailPermission'])->name('detail-permission');
    Route::post('update-permission', [RoleAndPermissionController::class,'updatePermission'])->name('update-permission');
    Route::post('delete-permission', [RoleAndPermissionController::class,'deletePermission'])->name('delete-permission');
    
    Route::get('fetch-permission-in-role', [RoleAndPermissionController::class,'fetchPermissionInRole'])->name('fetch-permission-in-role');
    Route::get('fetch-permission-in-role-v2', [RoleAndPermissionController::class,'fetchPermissionInRoleV2'])->name('fetch-permission-in-role-v2');
    Route::post('add-or-remove-permission', [RoleAndPermissionController::class,'addOrRemovePermissionToRole'])->name('add-or-remove-permission');

    Route::get('export-pdf', [PartnerManagementController::class,'exportPartnerToPdf'])->name('export-pdf');
    Route::get('export-excel', [PartnerManagementController::class,'exportPartnerToExcel'])->name('export-excel');
    Route::get('getMenusWithSubmenus', [PartnerManagementController::class,'getMenusWithSubmenus'])->name('getMenusWithSubmenus');

    Route::get('menu-setting', [MenuController::class,'index'])->name('menu-setting');
    Route::get('fetch-menu', [MenuController::class,'fetchMenu'])->name('fetch-menu');
    Route::post('store-menu', [MenuController::class,'storeMenu'])->name('store-menu');

    Route::get('approval-setting', [ApprovalSettingController::class,'index'])->name('approval-setting');
    Route::get('fetch-approval', [ApprovalSettingController::class,'fetchApproval'])->name('fetch-approval');
    Route::get('fetch-user-approval', [ApprovalSettingController::class,'fetchUserApproval'])->name('fetch-user-approval');
    Route::get('fetch-office-department', [ApprovalSettingController::class,'fetchOfficeAndDepartment'])->name('fetch-office-department');
    Route::post('store-approval-master', [ApprovalSettingController::class,'storeApprovalMaster'])->name('store-approval-master');
    Route::post('store-approval-detail', [ApprovalSettingController::class,'storeApprovalDetail'])->name('store-approval-detail');

    // Route::get('role-permission', [RolePermissionController::class,'index'])->name('role-permission');
});

Route::middleware(['auth', 'role:super-user|admin|super-admin'])->group(function () {
    Route::get('/partner-management', [PartnerManagementController::class,'index'])->name('partner-management');
    Route::get('/fetch-partner-list', [PartnerManagementController::class,'fetchPartner'])->name('fetch-partner-list');
    Route::get('/fetch-partner-detail', [PartnerManagementController::class,'detailPartner'])->name('fetch-partner-detail');
    Route::post('approval-partner', [PartnerManagementController::class,'approvalPartner'])->name('approval-partner');
});

// Route::get('fetch-data-partner', [ApiPartnerController::class, 'fetchPartner'])->name('fetch-data-partner');
// Route::post('blacklist-partner', [ApiPartnerController::class, 'blacklistPartner']);
Route::middleware(['auth:sanctum'])->group(function() {});


Auth::routes();

// Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
