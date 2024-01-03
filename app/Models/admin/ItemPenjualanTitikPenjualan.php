<?php

namespace App\Models\admin;

use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\TransaksiPenjualanTitikPenjualan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemPenjualanTitikPenjualan extends Model
{
    use HasFactory;
    
    protected $table = 'item_penjualan_titik_penjualan';

    protected $guarded = ['id'];

    public function transaksi_penjualan(){
        return $this->belongsTo(TransaksiPenjualanTitikPenjualan::class, 'transaksi_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
