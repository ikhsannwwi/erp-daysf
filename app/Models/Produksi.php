<?php

namespace App\Models;

use App\Models\Gudang;
use App\Models\Formula;
use App\Models\admin\Produk;
use App\Models\ProduksiDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produksi extends Model
{
    use HasFactory;
    
    protected $table = 'produksi';

    protected $guarded = ['id'];
    
    public function detail(){
        return $this->hasMany(ProduksiDetail::class,'produksi_id', 'id');
    }
    
    public function produk(){
        return $this->belongsTo(Produk::class,'produk_id', 'id');
    }
    
    public function gudang(){
        return $this->belongsTo(Gudang::class,'gudang_id', 'id');
    }
    
    public function formula(){
        return $this->belongsTo(Formula::class,'formula_id', 'id');
    }
}
