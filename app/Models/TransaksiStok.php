<?php

namespace App\Models;

use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiStok extends Model
{
    use HasFactory;

    protected $table = 'transaksi_stok';
    
    protected $guarded = ['id'];

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
