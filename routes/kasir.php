<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\kasir\DashboardController;
use App\Http\Controllers\kasir\TransaksiController;

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

Route::prefix('kasir')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('kasir.login');
    Route::post('loginProses', [AuthController::class, 'loginProses'])->name('kasir.loginProses');
    Route::get('logout', [AuthController::class, 'logout'])->name('kasir.logout');
    
    Route::middleware(['auth.admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('kasir.dashboard');

        Route::get('transaksi', [TransaksiController::class, 'index'])->name('kasir.transaksi');
        Route::post('transaksi/save', [TransaksiController::class, 'save'])->name('kasir.transaksi.save');
        Route::post('transaksi/uploadBarcode', [TransaksiController::class, 'uploadBarcode'])->name('kasir.transaksi.uploadBarcode');
        Route::get('transaksi/getDataProduk', [TransaksiController::class, 'getDataProduk'])->name('kasir.transaksi.getDataProduk');
        Route::get('transaksi/getDataMember', [TransaksiController::class, 'getDataMember'])->name('kasir.transaksi.getDataMember');
    });
});