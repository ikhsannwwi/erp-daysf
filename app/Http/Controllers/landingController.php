<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class landingController extends Controller
{
    public function index(){
        return view('landing.home');
    }
    public function game_android(){
        $title = 'Game Android';
        return view('landing.game',compact('title'));
    }
    public function game_android_mod(){
        $title = 'Game Android Mod';
        return view('landing.game',compact('title'));
    }
    public function game_pc(){
        $title = 'Game PC';
        return view('landing.game',compact('title'));
    }
    public function about_us(){
        return view('landing.about-us');
    }
    public function profile(){
        return view('landing.profile');
    }
    public function detail_app(){
        return view('landing.detail-app');
    }
}
