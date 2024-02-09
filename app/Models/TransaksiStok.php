<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStok extends Model
{
    use HasFactory;

    protected $table = 'transaksi_stok';
    
    protected $guarded = ['id'];
}
