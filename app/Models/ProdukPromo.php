<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\ProdukPromoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukPromo extends Model
{
    use HasFactory;

    protected $table = 'produk_promo';

    protected $guarded = ['id'];

    public function detail(){
        return $this->hasMany(ProdukPromoDetail::class,'produk_promo_id', 'id');
    }
}
