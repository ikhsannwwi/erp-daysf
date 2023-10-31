<?php

namespace App\Models\admin;

use App\Models\admin\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profile';

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_kode', 'kode');
    }
    
}
