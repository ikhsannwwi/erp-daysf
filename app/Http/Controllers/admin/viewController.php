<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class viewController extends Controller
{
    public function main_admin(){
        return view('admin.main');
    }
}
