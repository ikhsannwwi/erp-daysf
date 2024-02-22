<?php

namespace App\Models\admin;

use App\Models\admin\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $guarded = ['id'];

    public function produk(){
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
