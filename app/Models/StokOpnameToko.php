<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\Karyawan;
use App\Models\StokOpnameTokoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokOpnameToko extends Model
{
    use HasFactory;
    
    protected $table = 'stok_opname_toko';

    protected $guarded = ['id'];

    public function detail(){
        return $this->hasMany(StokOpnameTokoDetail::class,'stok_opname_toko_id', 'id');
    }

    public function toko(){
        return $this->belongsTo(Toko::class,'toko_id', 'id');
    }

    public function karyawan(){
        return $this->belongsTo(Karyawan::class,'karyawan_id', 'id');
    }
}
