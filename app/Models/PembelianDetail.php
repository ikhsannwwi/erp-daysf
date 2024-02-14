<?php

namespace App\Models;

use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';

    protected $guarded = ['id'];

    public function master(){
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }

    public function gudang(){
        return $this->belongsTo(Gudang::class, 'gudang_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
