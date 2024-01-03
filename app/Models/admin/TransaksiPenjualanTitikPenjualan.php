<?php

namespace App\Models\admin;

use App\Models\admin\Member;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\ItemPenjualanTitikPenjualan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\admin\PembayaranTransaksiPenjualanTitikPenjualan;

class TransaksiPenjualanTitikPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan_titik_penjualan';

    protected $guarded = ['id'];

    public function member(){
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
    
    public function item(){
        return $this->hasMany(ItemPenjualanTitikPenjualan::class, 'transaksi_id');
    }
    
    public function pembayaran(){
        return $this->hasMany(PembayaranTransaksiPenjualanTitikPenjualan::class, 'transaksi_id');
    }
}
