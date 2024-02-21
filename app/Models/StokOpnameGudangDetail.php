<?php

namespace App\Models;

use App\Models\admin\Produk;
use App\Models\StokOpnameGudang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokOpnameGudangDetail extends Model
{
    use HasFactory;

    protected $table = 'stok_opname_gudang_detail';

    protected $guarded = ['id'];

    public function master(){
        return $this->belongsTo(StokOpnameGudang::class, 'stok_opname_gudang_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
