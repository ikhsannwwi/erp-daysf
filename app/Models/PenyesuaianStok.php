<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\Gudang;
use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenyesuaianStok extends Model
{
    use HasFactory;

    protected $table = 'penyesuaian_stok';

    protected $guarded = ['id'];

    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }

    public function toko(){
        return $this->belongsTo(Toko::class);
    }

    public function produk(){
        return $this->belongsTo(Produk::class);
    }
}
