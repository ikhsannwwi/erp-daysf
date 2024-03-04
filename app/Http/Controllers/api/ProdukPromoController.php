<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\ProdukPromo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukPromoController extends Controller
{
    public function index(Request $request){
        $today = Carbon::now('Asia/Jakarta');

        $query = ProdukPromo::with([
            'detail' => function ($parrentQuery) {
                $parrentQuery->with([
                    'produk' => function ($queryChild) {
                        $queryChild->with('image');
                        $queryChild->where('status', 1)
                            ->where('e_commerce', 1);
                    }
                ]);
            }
        ])
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_berakhir', '>=', $today);

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
}
