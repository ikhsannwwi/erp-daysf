<?php

namespace App\Models;

use App\Models\Produksi;
use App\Models\admin\Produk;
use App\Models\FormulaDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduksiDetail extends Model
{
    use HasFactory;

    protected $table = 'produksi_detail';

    protected $guarded = ['id'];
    
    public function master(){
        return $this->belongsTo(Produksi::class, 'produksi_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function formula_detail(){
        return $this->belongsTo(FormulaDetail::class, 'formula_detail_id', 'id');
    }
}
