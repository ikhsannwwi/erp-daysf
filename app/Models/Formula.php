<?php

namespace App\Models;

use App\Models\admin\Produk;
use App\Models\FormulaDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formula extends Model
{
    use HasFactory;
    
    protected $table = 'formula';

    protected $guarded = ['id'];
    
    public function detail(){
        return $this->hasMany(FormulaDetail::class,'formula_id', 'id');
    }
    
    public function produk(){
        return $this->belongsTo(Produk::class,'produk_id', 'id');
    }
}
