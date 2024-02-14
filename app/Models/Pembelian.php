<?php

namespace App\Models;

use App\Models\admin\Supplier;
use App\Models\PembelianDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $guarded = ['id'];

    public function detail(){
        return $this->hasMany(PembelianDetail::class,'pembelian_id', 'id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id', 'id');
    }
}
