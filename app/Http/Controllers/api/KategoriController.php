<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\admin\Kategori;
use App\Http\Controllers\Controller;

class KategoriController extends Controller
{
    public function index(Request $request){
        
        $query = Kategori::with([
            'produk' => function ($queryChild) {
                $queryChild->with([
                    'promo' => function ($queryPromo) {
                        $today = now('Asia/Jakarta');
        
                        $queryPromo->whereHas('master', function ($queryPromoMaster) use ($today) {
                            // Check if 'master' relationship is not null and apply date conditions
                            $queryPromoMaster->where('tanggal_mulai', '<=', $today)
                                ->where('tanggal_berakhir', '>=', $today)
                                ->orderBy('tanggal_berakhir', 'asc') // Order within the subquery
                                ->take(1);
                        });
                    },
                ]);
                $queryChild->with('image');
                $queryChild->where('status', 1)
                    ->where('e_commerce', 1);
            },
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
