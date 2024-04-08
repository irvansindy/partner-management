<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\UserManagementController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/profile', [PartnerController::class,'index'])->name('profile');
Route::get('/fetch-profile', [PartnerController::class,'fetchCompany'])->name('fetch-profile');
Route::get('/fetch-doctype', [PartnerController::class,'fetchDocTypeCategories'])->name('fetch-doctype');

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
Route::post('add-or-remove-permission', [RoleAndPermissionController::class,'addOrRemovePermissionToRole'])->name('add-or-remove-permission');

Route::get('/user-manage', [UserManagementController::class,'index'])->name('user-manage');
Route::get('/fetch-user', [UserManagementController::class,'fetchUser'])->name('fetch-user');
Route::post('/store-user', [UserManagementController::class,'storeUser'])->name('store-user');
Route::get('/detail-user', [UserManagementController::class,'detailUser'])->name('detail-user');
Route::post('/update-user', [UserManagementController::class,'updateUser'])->name('update-user');
Route::post('/delete-user', [UserManagementController::class,'deleteUser'])->name('delete-user');

// Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
