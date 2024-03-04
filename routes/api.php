<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TokoController;
use App\Http\Controllers\api\ProdukController;
use App\Http\Controllers\api\KategoriController;
use App\Http\Controllers\api\ProdukPromoController;

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

//Produk
Route::get('produk', [ProdukController::class, 'index'])->name('api.produk');
Route::get('produk/detail', [ProdukController::class, 'detail'])->name('api.produk.detail');

//Kategori
Route::get('kategori', [KategoriController::class, 'index'])->name('api.kategori');
Route::get('kategori/detail', [KategoriController::class, 'detail'])->name('api.kategori.detail');

//ProdukPromo
Route::get('promo', [ProdukPromoController::class, 'index'])->name('api.produk_promo');
Route::get('promo/detail', [ProdukPromoController::class, 'detail'])->name('api.produk_promo.detail');

//Toko
Route::get('toko', [TokoController::class, 'index'])->name('api.toko');
Route::get('toko/detail', [TokoController::class, 'detail'])->name('api.toko.detail');