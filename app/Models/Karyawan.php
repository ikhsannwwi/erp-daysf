<?php

namespace App\Models;

use App\Models\Departemen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;
    
    protected $table = 'karyawan';

    protected $guarded = ['id'];
    
    public function departemen(){
        return $this->belongsTo(Departemen::class, 'departemen_id', 'id');
    }
}
