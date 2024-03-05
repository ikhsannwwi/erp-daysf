<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Http\Controllers\Controller;

class BestSellerController extends Controller
{
    public function index(){
        // Mengelompokkan data berdasarkan produk_id dan jenis_transaksi
        $data = TransaksiStok::with([
            'produk' => function($queryProduk){
                $queryProduk->with([
                    'image',
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
            }
        ])
            ->select('produk_id', 'metode_transaksi', \DB::raw('SUM(jumlah_unit) as total_unit'))
            ->groupBy('produk_id', 'metode_transaksi')
            ->where('metode_transaksi', 'keluar')
            ->whereIn('jenis_transaksi', ['kasir_transaksi', 'transaksi_penjualan'])
            ->orderBy('total_unit', 'desc') // Use orderBy instead of sortBy
            ->take(5)
            ->get(); // Add get() to execute the query and retrieve the results
        
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data berhasil dimuat',
            'data' => $data
        ], 200);
    }
    
    
}
