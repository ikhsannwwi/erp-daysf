<?php

namespace App\Models\admin;

use App\Models\admin\ModuleAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    protected $table = 'module';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function access()
    {
        return $this->hasMany(ModuleAccess::class, 'module_id');
    }
}
