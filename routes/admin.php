<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\TokoController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\viewController;
use App\Http\Controllers\admin\GudangController;
use App\Http\Controllers\admin\MemberController;
use App\Http\Controllers\admin\ModuleController;
use App\Http\Controllers\admin\ProdukController;
use App\Http\Controllers\admin\SatuanController;
use App\Http\Controllers\admin\FormulaController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\admin\ProduksiController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LogSystemController;
use App\Http\Controllers\admin\PembelianController;
use App\Http\Controllers\admin\UserGroupController;
use App\Http\Controllers\admin\OperatorKasirController;
use App\Http\Controllers\admin\TransaksiStokController;
use App\Http\Controllers\admin\SatuanKonversiController;
use App\Http\Controllers\admin\PenyesuaianStokController;
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

        //Kategori
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
        Route::get('produk/getDataSatuan', [ProdukController::class, 'getDataSatuan'])->name('admin.produk.getDataSatuan');
        Route::get('produk/cetak/{kode}', [ProdukController::class, 'cetak'])->name('admin.produk.cetak');
        
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
        
        //Transaksi Stok
        Route::get('transaksi-stok', [TransaksiStokController::class, 'index'])->name('admin.transaksi_stok');
        Route::get('transaksi-stok/{gudang_id}/add/{kode}', [TransaksiStokController::class, 'add'])->name('admin.transaksi_stok.add');
        Route::get('transaksi-stok/getData', [TransaksiStokController::class, 'getData'])->name('admin.transaksi_stok.getData');
        Route::get('transaksi-stok/getDataProduk', [TransaksiStokController::class, 'getDataProduk'])->name('admin.transaksi_stok.getDataProduk');
        Route::post('transaksi-stok/save', [TransaksiStokController::class, 'save'])->name('admin.transaksi_stok.save');
        Route::get('transaksi-stok/edit/{id}', [TransaksiStokController::class, 'edit'])->name('admin.transaksi_stok.edit');
        Route::put('transaksi-stok/update', [TransaksiStokController::class, 'update'])->name('admin.transaksi_stok.update');
        Route::delete('transaksi-stok/delete', [TransaksiStokController::class, 'delete'])->name('admin.transaksi_stok.delete');
        Route::get('transaksi-stok/{gudang_id}/detail/{kode}', [TransaksiStokController::class, 'detail'])->name('admin.transaksi_stok.detail');
        Route::post('transaksi-stok/checkEmail',[TransaksiStokController::class, 'checkEmail'])->name('admin.transaksi_stok.checkEmail');
        Route::post('transaksi-stok/checkTelepon',[TransaksiStokController::class, 'checkTelepon'])->name('admin.transaksi_stok.checkTelepon');
        Route::get('transaksi-stok/getGudang', [TransaksiStokController::class, 'getGudang'])->name('admin.transaksi_stok.getGudang');

        //Gudang
        Route::get('gudang', [GudangController::class, 'index'])->name('admin.gudang');
        Route::get('gudang/add', [GudangController::class, 'add'])->name('admin.gudang.add');
        Route::get('gudang/getData', [GudangController::class, 'getData'])->name('admin.gudang.getData');
        Route::post('gudang/save', [GudangController::class, 'save'])->name('admin.gudang.save');
        Route::get('gudang/edit/{id}', [GudangController::class, 'edit'])->name('admin.gudang.edit');
        Route::put('gudang/update', [GudangController::class, 'update'])->name('admin.gudang.update');
        Route::delete('gudang/delete', [GudangController::class, 'delete'])->name('admin.gudang.delete');
        Route::get('gudang/getDetail-{id}', [GudangController::class, 'getDetail'])->name('admin.gudang.getDetail');
        Route::post('gudang/checkName',[GudangController::class, 'checkName'])->name('admin.gudang.checkName');
        Route::post('gudang/changeStatus',[GudangController::class, 'changeStatus'])->name('admin.gudang.changeStatus');
        
        //Penyesuaian Stok
        Route::get('penyesuaian-stok', [PenyesuaianStokController::class, 'index'])->name('admin.penyesuaian_stok');
        Route::get('penyesuaian-stok/add', [PenyesuaianStokController::class, 'add'])->name('admin.penyesuaian_stok.add');
        Route::get('penyesuaian-stok/getData', [PenyesuaianStokController::class, 'getData'])->name('admin.penyesuaian_stok.getData');
        Route::post('penyesuaian-stok/save', [PenyesuaianStokController::class, 'save'])->name('admin.penyesuaian_stok.save');
        Route::get('penyesuaian-stok/edit/{id}', [PenyesuaianStokController::class, 'edit'])->name('admin.penyesuaian_stok.edit');
        Route::put('penyesuaian-stok/update', [PenyesuaianStokController::class, 'update'])->name('admin.penyesuaian_stok.update');
        Route::delete('penyesuaian-stok/delete', [PenyesuaianStokController::class, 'delete'])->name('admin.penyesuaian_stok.delete');
        Route::get('penyesuaian-stok/getDetail-{id}', [PenyesuaianStokController::class, 'getDetail'])->name('admin.penyesuaian_stok.getDetail');
        Route::get('penyesuaian-stok/getDataGudang', [PenyesuaianStokController::class, 'getDataGudang'])->name('admin.penyesuaian_stok.getDataGudang');
        Route::get('penyesuaian-stok/getDataProduk', [PenyesuaianStokController::class, 'getDataProduk'])->name('admin.penyesuaian_stok.getDataProduk');
        Route::post('penyesuaian-stok/checkStock', [PenyesuaianStokController::class, 'checkStock'])->name('admin.penyesuaian_stok.checkStock');
        
        //Pembelian
        Route::get('pembelian', [PembelianController::class, 'index'])->name('admin.pembelian');
        Route::get('pembelian/add', [PembelianController::class, 'add'])->name('admin.pembelian.add');
        Route::get('pembelian/getData', [PembelianController::class, 'getData'])->name('admin.pembelian.getData');
        Route::post('pembelian/save', [PembelianController::class, 'save'])->name('admin.pembelian.save');
        Route::get('pembelian/edit/{id}', [PembelianController::class, 'edit'])->name('admin.pembelian.edit');
        Route::put('pembelian/update', [PembelianController::class, 'update'])->name('admin.pembelian.update');
        Route::delete('pembelian/delete', [PembelianController::class, 'delete'])->name('admin.pembelian.delete');
        Route::get('pembelian/getDetail-{id}', [PembelianController::class, 'getDetail'])->name('admin.pembelian.getDetail');
        Route::get('pembelian/getDataSupplier', [PembelianController::class, 'getDataSupplier'])->name('admin.pembelian.getDataSupplier');
        Route::get('pembelian/getDataGudang', [PembelianController::class, 'getDataGudang'])->name('admin.pembelian.getDataGudang');
        Route::get('pembelian/getDataSatuan', [PembelianController::class, 'getDataSatuan'])->name('admin.pembelian.getDataSatuan');
        Route::get('pembelian/getDataProduk', [PembelianController::class, 'getDataProduk'])->name('admin.pembelian.getDataProduk');
        Route::post('pembelian/checkStock', [PembelianController::class, 'checkStock'])->name('admin.pembelian.checkStock');
        Route::put('pembelian/updateTotal', [PembelianController::class, 'updateTotal'])->name('admin.pembelian.updateTotal');
        Route::delete('pembelian/deleteDetail', [PembelianController::class, 'deleteDetail'])->name('admin.pembelian.deleteDetail');

        //Satuan
        Route::get('satuan', [SatuanController::class, 'index'])->name('admin.satuan');
        Route::get('satuan/add', [SatuanController::class, 'add'])->name('admin.satuan.add');
        Route::get('satuan/getData', [SatuanController::class, 'getData'])->name('admin.satuan.getData');
        Route::post('satuan/save', [SatuanController::class, 'save'])->name('admin.satuan.save');
        Route::get('satuan/edit/{id}', [SatuanController::class, 'edit'])->name('admin.satuan.edit');
        Route::put('satuan/update', [SatuanController::class, 'update'])->name('admin.satuan.update');
        Route::delete('satuan/delete', [SatuanController::class, 'delete'])->name('admin.satuan.delete');
        Route::post('satuan/checkNama',[SatuanController::class, 'checkNama'])->name('admin.satuan.checkNama');

        //Satuan Konversi
        Route::get('satuan-konversi', [SatuanKonversiController::class, 'index'])->name('admin.satuan_konversi');
        Route::get('satuan-konversi/add', [SatuanKonversiController::class, 'add'])->name('admin.satuan_konversi.add');
        Route::get('satuan-konversi/getData', [SatuanKonversiController::class, 'getData'])->name('admin.satuan_konversi.getData');
        Route::post('satuan-konversi/save', [SatuanKonversiController::class, 'save'])->name('admin.satuan_konversi.save');
        Route::get('satuan-konversi/edit/{id}', [SatuanKonversiController::class, 'edit'])->name('admin.satuan_konversi.edit');
        Route::put('satuan-konversi/update', [SatuanKonversiController::class, 'update'])->name('admin.satuan_konversi.update');
        Route::delete('satuan-konversi/delete', [SatuanKonversiController::class, 'delete'])->name('admin.satuan_konversi.delete');
        Route::get('satuan-konversi/getDetail-{id}', [SatuanKonversiController::class, 'getDetail'])->name('admin.satuan_konversi.getDetail');
        Route::get('satuan-konversi/getDataProduk', [SatuanKonversiController::class, 'getDataProduk'])->name('admin.satuan_konversi.getDataProduk');
        Route::post('satuan-konversi/changeStatus',[SatuanKonversiController::class, 'changeStatus'])->name('admin.satuan_konversi.changeStatus');
        
        //Toko
        Route::get('toko', [TokoController::class, 'index'])->name('admin.toko');
        Route::get('toko/add', [TokoController::class, 'add'])->name('admin.toko.add');
        Route::get('toko/getData', [TokoController::class, 'getData'])->name('admin.toko.getData');
        Route::post('toko/save', [TokoController::class, 'save'])->name('admin.toko.save');
        Route::get('toko/edit/{id}', [TokoController::class, 'edit'])->name('admin.toko.edit');
        Route::put('toko/update', [TokoController::class, 'update'])->name('admin.toko.update');
        Route::delete('toko/delete', [TokoController::class, 'delete'])->name('admin.toko.delete');
        Route::get('toko/getDetail-{id}', [TokoController::class, 'getDetail'])->name('admin.toko.getDetail');
        Route::post('toko/checkName',[TokoController::class, 'checkName'])->name('admin.toko.checkName');
        Route::post('toko/changeStatus',[TokoController::class, 'changeStatus'])->name('admin.toko.changeStatus');
        
        //Formula
        Route::get('formula', [FormulaController::class, 'index'])->name('admin.formula');
        Route::get('formula/add', [FormulaController::class, 'add'])->name('admin.formula.add');
        Route::get('formula/getData', [FormulaController::class, 'getData'])->name('admin.formula.getData');
        Route::post('formula/save', [FormulaController::class, 'save'])->name('admin.formula.save');
        Route::get('formula/edit/{id}', [FormulaController::class, 'edit'])->name('admin.formula.edit');
        Route::put('formula/update', [FormulaController::class, 'update'])->name('admin.formula.update');
        Route::delete('formula/delete', [FormulaController::class, 'delete'])->name('admin.formula.delete');
        Route::get('formula/getDetail-{id}', [FormulaController::class, 'getDetail'])->name('admin.formula.getDetail');
        Route::get('formula/getDataGudang', [FormulaController::class, 'getDataGudang'])->name('admin.formula.getDataGudang');
        Route::get('formula/getDataSatuan', [FormulaController::class, 'getDataSatuan'])->name('admin.formula.getDataSatuan');
        Route::get('formula/getDataProduk', [FormulaController::class, 'getDataProduk'])->name('admin.formula.getDataProduk');
        Route::get('formula/getDataProdukProduksi', [FormulaController::class, 'getDataProdukProduksi'])->name('admin.formula.getDataProdukProduksi');
        Route::delete('formula/deleteDetail', [FormulaController::class, 'deleteDetail'])->name('admin.formula.deleteDetail');
        
        //Produksi
        Route::get('produksi', [ProduksiController::class, 'index'])->name('admin.produksi');
        Route::get('produksi/add', [ProduksiController::class, 'add'])->name('admin.produksi.add');
        Route::get('produksi/getData', [ProduksiController::class, 'getData'])->name('admin.produksi.getData');
        Route::post('produksi/save', [ProduksiController::class, 'save'])->name('admin.produksi.save');
        Route::get('produksi/edit/{id}', [ProduksiController::class, 'edit'])->name('admin.produksi.edit');
        Route::put('produksi/update', [ProduksiController::class, 'update'])->name('admin.produksi.update');
        Route::delete('produksi/delete', [ProduksiController::class, 'delete'])->name('admin.produksi.delete');
        Route::get('produksi/getDetail-{id}', [ProduksiController::class, 'getDetail'])->name('admin.produksi.getDetail');
        Route::get('produksi/getDataFormula', [ProduksiController::class, 'getDataFormula'])->name('admin.produksi.getDataFormula');
        Route::get('produksi/getFormulaDetail', [ProduksiController::class, 'getFormulaDetail'])->name('admin.produksi.getFormulaDetail');
        Route::get('produksi/getDataGudang', [ProduksiController::class, 'getDataGudang'])->name('admin.produksi.getDataGudang');
        Route::get('produksi/getDataProduk', [ProduksiController::class, 'getDataProduk'])->name('admin.produksi.getDataProduk');
        Route::post('produksi/checkStock', [ProduksiController::class, 'checkStock'])->name('admin.produksi.checkStock');
        Route::delete('produksi/deleteDetail', [ProduksiController::class, 'deleteDetail'])->name('admin.produksi.deleteDetail');
    });
});
