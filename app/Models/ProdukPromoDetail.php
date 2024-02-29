<?php

namespace App\Models;

use App\Models\ProdukPromo;
use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukPromoDetail extends Model
{
    use HasFactory;

    protected $table = 'produk_promo_detail';

    protected $guarded = ['id'];

    public function master(){
        return $this->belongsTo(ProdukPromo::class, 'produk_promo_id', 'id');
    }

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
