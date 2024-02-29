<?php

namespace App\Http\Controllers\api;

use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    public function index(){
        $data = Produk::with('kategori')
                        ->with('image')
                        ->where('e_commerce', 1)
                        ->where('status', 1)
                        ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }

    public function detail(Request $request){
        $data = Produk::with('kategori')
                        ->where('e_commerce', 1)
                        ->where('status', 1)
                        ->where('kode', $request->kode)
                        ->first();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }
}
