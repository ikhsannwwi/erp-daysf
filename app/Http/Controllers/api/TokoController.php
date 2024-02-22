<?php

namespace App\Http\Controllers\api;

use App\Models\Toko;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokoController extends Controller
{
    public function index(){
        $data = Toko::where('status', 1)
                        ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }

    public function detail(Request $request){
        $data = Toko::where('kode', $request->kode)
                        ->first();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }
}
