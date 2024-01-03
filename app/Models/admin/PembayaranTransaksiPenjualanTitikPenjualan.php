<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTransaksiPenjualanTitikPenjualan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_transaksi_penjualan_titik_penjualan';

    protected $guarded = ['id'];
}
