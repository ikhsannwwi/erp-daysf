<?php

namespace App\Models\admin;

use App\Models\admin\UserMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'member';

    protected $guarded = ['id'];
    
    public function user_member(){
        return $this->hasOne(UserMember::class, 'kode', 'user_kode');
    }
}
