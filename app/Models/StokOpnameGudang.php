<?php

namespace App\Models;

use App\Models\Gudang;
use App\Models\Karyawan;
use App\Models\StokOpnameGudangDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokOpnameGudang extends Model
{
    use HasFactory;
    
    protected $table = 'stok_opname_gudang';

    protected $guarded = ['id'];

    public function detail(){
        return $this->hasMany(StokOpnameGudangDetail::class,'stok_opname_gudang_id', 'id');
    }

    public function gudang(){
        return $this->belongsTo(Gudang::class,'gudang_id', 'id');
    }

    public function karyawan(){
        return $this->belongsTo(Karyawan::class,'karyawan_id', 'id');
    }
}
