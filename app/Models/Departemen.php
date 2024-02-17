<?php

namespace App\Models;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';

    protected $guarded = ['id'];
    
    public function karyawan(){
        return $this->belongsTo(Karyawan::class, 'kepala_departemen', 'id');
    }
}
