<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\member\VerifyController;
use App\Http\Controllers\member\DashboardController;

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

Route::prefix('member')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('member.login');
    Route::post('loginProses', [AuthController::class, 'loginProses'])->name('member.loginProses');
    Route::get('logout', [AuthController::class, 'logout'])->name('member.logout');
    
    Route::get('verified', [VerifyController::class, 'index'])->name('member.verified');
    Route::post('verified/sentLink', [VerifyController::class, 'sentLink'])->name('member.verified.sentLink');
    Route::get('verified/reset/{token}', [VerifyController::class, 'resetPassword'])->name('member.verified.reset');
    Route::put('verified/reset/{token}', [VerifyController::class, 'updatePassword'])->name('member.verified.update');
    Route::post('verified/checkEmail', [VerifyController::class, 'checkEmail'])->name('member.verified.checkEmail');

    Route::get('password/request', [VerifyController::class, 'request'])->name('member.password.request');
    Route::post('password/request', [VerifyController::class, 'email'])->name('member.password.email');
    Route::get('password/reset/{token}', [VerifyController::class, 'resetPassword'])->name('member.password.reset');
    Route::post('password/reset/{token}', [VerifyController::class, 'updatePassword'])->name('member.password.update');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('member.dashboard');
    });
});