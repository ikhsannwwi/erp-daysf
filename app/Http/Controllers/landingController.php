<?php

namespace App\Http\Controllers;

use QrCode;
use Illuminate\Http\Request;

class landingController extends Controller
{
    public function generateQrCode()
    {
        $data = QrCode::generate(
            'GFJK898DJJK',
        );
        return view('frontpage.qrcode', compact('data'));
    }
}
