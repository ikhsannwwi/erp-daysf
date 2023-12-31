<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\viewController;
use App\Http\Controllers\admin\MemberController;
use App\Http\Controllers\admin\ModuleController;
use App\Http\Controllers\admin\ProdukController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LogSystemController;
use App\Http\Controllers\admin\UserGroupController;
use App\Http\Controllers\admin\OperatorKasirController;
use App\Http\Controllers\admin\TransaksiPenjualanController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ------------------------------------------  Admin -----------------------------------------------------------------
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('loginProses', [AuthController::class, 'loginProses'])->name('admin.loginProses');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    Route::get('main-admin', [viewController::class, 'main_admin'])->name('main_admin');

    Route::middleware(['auth.admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        //Log Systems
        Route::get('log-systems', [LogSystemController::class, 'index'])->name('admin.logSystems');
        Route::get('log-systems/getData', [LogSystemController::class, 'getData'])->name('admin.logSystems.getData');
        Route::get('log-systems/getDataModule', [LogSystemController::class, 'getDataModule'])->name('admin.logSystems.getDataModule');
        Route::get('log-systems/getDataUser', [LogSystemController::class, 'getDataUser'])->name('admin.logSystems.getDataUser');
        Route::get('log-systems/getDetail{id}', [LogSystemController::class, 'getDetail'])->name('admin.logSystems.getDetail');
        Route::get('log-systems/clearLogs', [LogSystemController::class, 'clearLogs'])->name('admin.logSystems.clearLogs');
        Route::get('log-systems/generatePDF', [LogSystemController::class, 'generatePDF'])->name('admin.logSystems.generatePDF');
    
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
        Route::get('users/getUserGroup', [UserController::class, 'getUserGroup'])->name('admin.users.getUserGroup');
        Route::post('users/changeStatus',[UserController::class, 'changeStatus'])->name('admin.users.changeStatus');
        Route::get('users/generateKode',[UserController::class, 'generateKode'])->name('admin.users.generateKode');
        Route::post('users/checkEmail',[UserController::class, 'checkEmail'])->name('admin.users.checkEmail');
        Route::post('users/checkKode',[UserController::class, 'checkKode'])->name('admin.users.checkKode');

        Route::get('users/arsip',[UserController::class, 'arsip'])->name('admin.users.arsip');
        Route::get('users/arsip/getDataArsip',[UserController::class, 'getDataArsip'])->name('admin.users.getDataArsip');
        Route::put('users/arsip/restore',[UserController::class, 'restore'])->name('admin.users.restore');
        Route::delete('users/arsip/forceDelete',[UserController::class, 'forceDelete'])->name('admin.users.forceDelete');
        
        //Operator Kasir
        Route::get('operator-kasir', [OperatorKasirController::class, 'index'])->name('admin.operator_kasir');
        Route::get('operator-kasir/add', [OperatorKasirController::class, 'add'])->name('admin.operator_kasir.add');
        Route::get('operator-kasir/getData', [OperatorKasirController::class, 'getData'])->name('admin.operator_kasir.getData');
        Route::post('operator-kasir/save', [OperatorKasirController::class, 'save'])->name('admin.operator_kasir.save');
        Route::get('operator-kasir/edit/{id}', [OperatorKasirController::class, 'edit'])->name('admin.operator_kasir.edit');
        Route::put('operator-kasir/update', [OperatorKasirController::class, 'update'])->name('admin.operator_kasir.update');
        Route::delete('operator-kasir/delete', [OperatorKasirController::class, 'delete'])->name('admin.operator_kasir.delete');
        Route::get('operator-kasir/getDetail-{id}', [OperatorKasirController::class, 'getDetail'])->name('admin.operator_kasir.getDetail');
        Route::get('operator-kasir/getUserGroup', [OperatorKasirController::class, 'getUserGroup'])->name('admin.operator_kasir.getUserGroup');
        Route::post('operator-kasir/changeStatus',[OperatorKasirController::class, 'changeStatus'])->name('admin.operator_kasir.changeStatus');
        Route::get('operator-kasir/generateKode',[OperatorKasirController::class, 'generateKode'])->name('admin.operator_kasir.generateKode');
        Route::post('operator-kasir/checkEmail',[OperatorKasirController::class, 'checkEmail'])->name('admin.operator_kasir.checkEmail');
        Route::post('operator-kasir/checkKode',[OperatorKasirController::class, 'checkKode'])->name('admin.operator_kasir.checkKode');

        Route::get('operator-kasir/arsip',[OperatorKasirController::class, 'arsip'])->name('admin.operator_kasir.arsip');
        Route::get('operator-kasir/arsip/getDataArsip',[OperatorKasirController::class, 'getDataArsip'])->name('admin.operator_kasir.getDataArsip');
        Route::put('operator-kasir/arsip/restore',[OperatorKasirController::class, 'restore'])->name('admin.operator_kasir.restore');
        Route::delete('operator-kasir/arsip/forceDelete',[OperatorKasirController::class, 'forceDelete'])->name('admin.operator_kasir.forceDelete');
        
        //Profile
        Route::get('profile/{kode}', [ProfileController::class, 'index'])->name('admin.profile');
        Route::get('profile/getData', [ProfileController::class, 'getData'])->name('admin.profile.getData');
        Route::put('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::get('profile/getDetail-{kode}', [ProfileController::class, 'getDetail'])->name('admin.profile.getDetail');
        Route::post('profile/checkEmail',[ProfileController::class, 'checkEmail'])->name('admin.profile.checkEmail');
        
        //Setting
        Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::put('settings/update', [SettingController::class, 'update'])->name('admin.settings.update');

        //Modul dan Modul Akses
        Route::get('module', [ModuleController::class, 'index'])->name('admin.module');
        Route::get('module/add', [ModuleController::class, 'add'])->name('admin.module.add');
        Route::get('module/getData', [ModuleController::class, 'getData'])->name('admin.module.getData');
        Route::post('module/save', [ModuleController::class, 'save'])->name('admin.module.save');
        Route::get('module/edit/{id}', [ModuleController::class, 'edit'])->name('admin.module.edit');
        Route::put('module/update', [ModuleController::class, 'update'])->name('admin.module.update');
        Route::delete('module/delete', [ModuleController::class, 'delete'])->name('admin.module.delete');
        Route::get('module/getDetail-{id}', [ModuleController::class, 'getDetail'])->name('admin.module.getDetail');

        //Modul dan Modul Akses
        Route::get('kategori', [KategoriController::class, 'index'])->name('admin.kategori');
        Route::get('kategori/add', [KategoriController::class, 'add'])->name('admin.kategori.add');
        Route::get('kategori/getData', [KategoriController::class, 'getData'])->name('admin.kategori.getData');
        Route::post('kategori/save', [KategoriController::class, 'save'])->name('admin.kategori.save');
        Route::get('kategori/edit/{id}', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
        Route::put('kategori/update', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('kategori/delete', [KategoriController::class, 'delete'])->name('admin.kategori.delete');
        Route::post('kategori/checkNama',[KategoriController::class, 'checkNama'])->name('admin.kategori.checkNama');

        //Produk
        Route::get('produk', [ProdukController::class, 'index'])->name('admin.produk');
        Route::get('produk/add', [ProdukController::class, 'add'])->name('admin.produk.add');
        Route::get('produk/getData', [ProdukController::class, 'getData'])->name('admin.produk.getData');
        Route::post('produk/save', [ProdukController::class, 'save'])->name('admin.produk.save');
        Route::get('produk/edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
        Route::put('produk/update', [ProdukController::class, 'update'])->name('admin.produk.update');
        Route::delete('produk/delete', [ProdukController::class, 'delete'])->name('admin.produk.delete');
        Route::get('produk/getDetail-{id}', [ProdukController::class, 'getDetail'])->name('admin.produk.getDetail');
        Route::get('produk/getKategori', [ProdukController::class, 'getKategori'])->name('admin.produk.getKategori');
        Route::post('produk/changeStatus',[ProdukController::class, 'changeStatus'])->name('admin.produk.changeStatus');
        Route::post('produk/checkNama',[ProdukController::class, 'checkNama'])->name('admin.produk.checkNama');

        Route::get('produk/arsip',[ProdukController::class, 'arsip'])->name('admin.produk.arsip');
        Route::get('produk/arsip/getDataArsip',[ProdukController::class, 'getDataArsip'])->name('admin.produk.getDataArsip');
        Route::put('produk/arsip/restore',[ProdukController::class, 'restore'])->name('admin.produk.restore');
        Route::delete('produk/arsip/forceDelete',[ProdukController::class, 'forceDelete'])->name('admin.produk.forceDelete');

        //Member
        Route::get('member', [MemberController::class, 'index'])->name('admin.member');
        Route::get('member/add', [MemberController::class, 'add'])->name('admin.member.add');
        Route::get('member/getData', [MemberController::class, 'getData'])->name('admin.member.getData');
        Route::post('member/save', [MemberController::class, 'save'])->name('admin.member.save');
        Route::get('member/edit/{id}', [MemberController::class, 'edit'])->name('admin.member.edit');
        Route::put('member/update', [MemberController::class, 'update'])->name('admin.member.update');
        Route::delete('member/delete', [MemberController::class, 'delete'])->name('admin.member.delete');
        Route::get('member/getDetail-{id}', [MemberController::class, 'getDetail'])->name('admin.member.getDetail');
        Route::get('member/getKategori', [MemberController::class, 'getKategori'])->name('admin.member.getKategori');
        Route::post('member/changeStatus',[MemberController::class, 'changeStatus'])->name('admin.member.changeStatus');
        Route::post('member/checkEmail',[MemberController::class, 'checkEmail'])->name('admin.member.checkEmail');
        Route::post('member/checkTelepon',[MemberController::class, 'checkTelepon'])->name('admin.member.checkTelepon');
        Route::get('member/getDataUserGroup', [MemberController::class, 'getDataUserGroup'])->name('admin.member.getDataUserGroup');
        Route::get('member/generateKode',[MemberController::class, 'generateKode'])->name('admin.member.generateKode');
        Route::post('member/checkKode',[UserController::class, 'checkKode'])->name('admin.member.checkKode');

        Route::get('member/arsip',[MemberController::class, 'arsip'])->name('admin.member.arsip');
        Route::get('member/arsip/getDataArsip',[MemberController::class, 'getDataArsip'])->name('admin.member.getDataArsip');
        Route::put('member/arsip/restore',[MemberController::class, 'restore'])->name('admin.member.restore');
        Route::delete('member/arsip/forceDelete',[MemberController::class, 'forceDelete'])->name('admin.member.forceDelete');

        //Supplier
        Route::get('supplier', [SupplierController::class, 'index'])->name('admin.supplier');
        Route::get('supplier/add', [SupplierController::class, 'add'])->name('admin.supplier.add');
        Route::get('supplier/getData', [SupplierController::class, 'getData'])->name('admin.supplier.getData');
        Route::post('supplier/save', [SupplierController::class, 'save'])->name('admin.supplier.save');
        Route::get('supplier/edit/{id}', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
        Route::put('supplier/update', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('supplier/delete', [SupplierController::class, 'delete'])->name('admin.supplier.delete');
        Route::get('supplier/getDetail-{id}', [SupplierController::class, 'getDetail'])->name('admin.supplier.getDetail');
        Route::post('supplier/checkEmail',[SupplierController::class, 'checkEmail'])->name('admin.supplier.checkEmail');
        Route::post('supplier/checkTelepon',[SupplierController::class, 'checkTelepon'])->name('admin.supplier.checkTelepon');
        
        //Transaksi Penjualan
        Route::get('transaksi-penjualan', [TransaksiPenjualanController::class, 'index'])->name('admin.transaksi_penjualan');
        Route::get('transaksi-penjualan/transaksi', [TransaksiPenjualanController::class, 'transaksi'])->name('admin.transaksi_penjualan.transaksi');
        Route::get('transaksi-penjualan/add', [TransaksiPenjualanController::class, 'add'])->name('admin.transaksi_penjualan.add');
        Route::get('transaksi-penjualan/getData', [TransaksiPenjualanController::class, 'getData'])->name('admin.transaksi_penjualan.getData');
        Route::get('transaksi-penjualan/getDataProduk', [TransaksiPenjualanController::class, 'getDataProduk'])->name('admin.transaksi_penjualan.getDataProduk');
        Route::get('transaksi-penjualan/getDataMember', [TransaksiPenjualanController::class, 'getDataMember'])->name('admin.transaksi_penjualan.getDataMember');
        Route::post('transaksi-penjualan/save', [TransaksiPenjualanController::class, 'save'])->name('admin.transaksi_penjualan.save');
        Route::get('transaksi-penjualan/edit/{id}', [TransaksiPenjualanController::class, 'edit'])->name('admin.transaksi_penjualan.edit');
        Route::put('transaksi-penjualan/update', [TransaksiPenjualanController::class, 'update'])->name('admin.transaksi_penjualan.update');
        Route::put('transaksi-penjualan/updateTotal', [TransaksiPenjualanController::class, 'updateTotal'])->name('admin.transaksi_penjualan.updateTotal');
        Route::delete('transaksi-penjualan/delete', [TransaksiPenjualanController::class, 'delete'])->name('admin.transaksi_penjualan.delete');
        Route::delete('transaksi-penjualan/deleteItem', [TransaksiPenjualanController::class, 'deleteItem'])->name('admin.transaksi_penjualan.deleteItem');
        Route::get('transaksi-penjualan/getDetail-{id}', [TransaksiPenjualanController::class, 'getDetail'])->name('admin.transaksi_penjualan.getDetail');
        Route::get('transaksi-penjualan/getMember', [TransaksiPenjualanController::class, 'getMember'])->name('admin.transaksi_penjualan.getMember');
        Route::get('transaksi-penjualan/getProduk', [TransaksiPenjualanController::class, 'getProduk'])->name('admin.transaksi_penjualan.getProduk');
        Route::get('transaksi-penjualan/getProductDetails', [TransaksiPenjualanController::class, 'getProductDetails'])->name('admin.transaksi_penjualan.getProductDetails');
        Route::post('transaksi-penjualan/uploadBarcode', [TransaksiPenjualanController::class, 'uploadBarcode'])->name('admin.transaksi_penjualan.uploadBarcode');
    });
});
