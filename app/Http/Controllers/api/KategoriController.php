<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\admin\Kategori;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    public function index(Request $request){
        $query = Kategori::with([
            'produk' => function($queryChild){
                $queryChild->with('image');
                $queryChild->where('status', 1)
                    ->where('e_commerce', 1);
            }
        ]);

        $notShow = json_decode(urldecode($request->notShow), true);

        if (!empty($notShow)) {
            $query->whereNotIn('nama', $notShow);
        }

        $data = $query->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }

    public function detail(Request $request){
        $query = Kategori::with([
            'produk' => function($queryChild){
                $queryChild->with('image');
                $queryChild->where('status', 1)
                    ->where('e_commerce', 1);
            }
        ])->where('id', $request->id);

        $data = $query->first();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }
}
