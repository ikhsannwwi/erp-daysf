<?php

namespace App\Models\admin;

use App\Models\Satuan;
use App\Models\admin\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'produk';

    protected $guarded = ['id'];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function satuan(){
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
