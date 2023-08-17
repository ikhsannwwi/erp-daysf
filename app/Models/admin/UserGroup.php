<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\UserGroupPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroup extends Model
{
    use HasFactory;

    protected $table = 'user_groups';

    protected $guarded = ['id'];

    public function permissions(){
        return $this->hasMany(UserGroupPermission::class, 'user_group_id');
    }
}
