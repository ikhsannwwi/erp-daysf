<?php

use App\Http\Controllers\admin\viewController;
use App\Http\Controllers\landingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
