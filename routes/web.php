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

Route::get('/', [landingController::class, 'index'])->name('index');
Route::get('/category/game-android', [landingController::class, 'game_android'])->name('game_android');
Route::get('/category/game-android-mod', [landingController::class, 'game_android_mod'])->name('game_android_mod');
Route::get('/category/game-pc', [landingController::class, 'game_pc'])->name('game_pc');
Route::get('/about-us', [landingController::class, 'about_us'])->name('about_us');
Route::get('/profile', [landingController::class, 'profile'])->name('profile');
Route::get('/detail-app', [landingController::class, 'detail_app'])->name('detail_app');


// ------------------------------------------  Admin -----------------------------------------------------------------
Route::get('/admin/main-admin', [viewController::class, 'main_admin'])->name('main_admin');