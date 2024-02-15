<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ClearController;
use App\Http\Controllers\landingController;
use App\Http\Controllers\admin\viewController;

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

Route::get('/ms-admin-ikhsannawawi', function () {
    Artisan::call('migrate:fresh --seed');
    return redirect()->route('index');
});
Route::get('/', function () {
    return redirect()->route('admin.login');
});
Route::get('/qrcode', [landingController::class, 'generateQrCode'])->name('web.index');

Route::prefix('clear')->group(function () {
    Route::get('/all', [ClearController::class, 'clearOptimize'])->name('clear.all');
    Route::get('/config', [ClearController::class, 'clearConfig'])->name('clear.config');
    Route::get('/cache', [ClearController::class, 'clearCache'])->name('clear.cache');
    Route::get('/migrate', [ClearController::class, 'migrate'])->name('migrate');
    Route::get('/fresh', [ClearController::class, 'migrateFresh'])->name('migrate.fresh');
    Route::get('/seeder', [ClearController::class, 'seeder'])->name('seeder');
    Route::get('/cart', [CartController::class, 'clearCart'])->name('clear_cart');
    Route::get('/storage', [ClearController::class, 'storageLink'])->name('storage');
    Route::get('/seed-permission', [ClearController::class, 'seedPermissions'])->name('seedPermissions');
});
