<?php

namespace App\Models;

use App\Models\Formula;
use App\Models\admin\Produk;
use App\Models\SatuanKonversi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormulaDetail extends Model
{
    use HasFactory;

    protected $table = 'formula_detail';

    protected $guarded = ['id'];
    
    public function master(){
        return $this->belongsTo(Formula::class, 'formula_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function satuan_konversi(){
        return $this->belongsTo(SatuanKonversi::class, 'satuan_id', 'id');
    }
}
