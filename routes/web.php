<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\administrator\DashboardController;
use App\Http\Controllers\administrator\UserGroupController;

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

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    //User Group
    Route::get('user-groups', [UserGroupController::class, 'index'])->name('admin.user_groups');
    Route::get('user-groups/add', [UserGroupController::class, 'add'])->name('admin.user_groups.add');
    Route::get('user-groups/getData', [UserGroupController::class, 'getData'])->name('admin.user_groups.getData');
    Route::post('user-groups/save', [UserGroupController::class, 'save'])->name('admin.user_groups.save');
    Route::get('user-groups/edit/{id}', [UserGroupController::class, 'edit'])->name('admin.user_groups.edit');
    Route::put('user-groups/update', [UserGroupController::class, 'update'])->name('admin.user_groups.update');
    Route::delete('user-groups/delete', [UserGroupController::class, 'delete'])->name('admin.user_groups.delete');
    Route::get('user-groups/getDetail-{id}', [UserGroupController::class, 'getDetail'])->name('admin.user_groups.getDetail');
    Route::post('user-groups/changeStatus',[UserGroupController::class, 'changeStatus'])->name('admin.user_groups.changeStatus');
    Route::post('user-groups/checkName',[UserGroupController::class, 'checkName'])->name('admin.user_groups.checkName');
    
    //User
    Route::get('users', [UserController::class, 'index'])->name('admin.users');
    Route::get('users/add', [UserController::class, 'add'])->name('admin.users.add');
    Route::get('users/getData', [UserController::class, 'getData'])->name('admin.users.getData');
    Route::post('users/save', [UserController::class, 'save'])->name('admin.users.save');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/update', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/delete', [UserController::class, 'delete'])->name('admin.users.delete');
    Route::get('users/getDetail-{id}', [UserController::class, 'getDetail'])->name('admin.users.getDetail');
    Route::post('users/changeStatus',[UserController::class, 'changeStatus'])->name('admin.users.changeStatus');
    Route::post('users/checkName',[UserController::class, 'checkName'])->name('admin.users.checkName');
});
