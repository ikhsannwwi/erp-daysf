<?php

namespace App\Models;

use App\Models\admin\Produk;
use App\Models\SatuanKonversi;
use App\Models\StokOpnameToko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokOpnameTokoDetail extends Model
{
    use HasFactory;

    protected $table = 'stok_opname_toko_detail';

    protected $guarded = ['id'];

    public function master(){
        return $this->belongsTo(StokOpnameToko::class, 'stok_opname_toko_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function satuan_konversi(){
        return $this->belongsTo(SatuanKonversi::class, 'satuan_id', 'id');
    }
}
