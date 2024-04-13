<?php

namespace App\Http\Controllers\kasir;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\admin\Member;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Http\Controllers\Controller;
use App\Models\admin\ItemPenjualanTitikPenjualan;
use App\Models\admin\TransaksiPenjualanTitikPenjualan;
use App\Models\admin\PembayaranTransaksiPenjualanTitikPenjualan;

class TransaksiController extends Controller
{
    private static $module = "kasir_transaksi";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('kasir.transaksi_penjualan.index');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }
        
        $rules = [
            'jumlah_total_transaksi' => 'required',
            'detail.*.input_id' => 'required',
            'detail.*.input_jumlah' => 'required',
            'detail.*.input_harga_satuan' => 'required',
            'detail.*.input_harga_total' => 'required',
            'jumlah_total_pembayaran_transaksi' => 'required',
        ];

        $request->validate($rules);

        // dd($request);

        function convertToRoman($number)
        {
            $romans = [
                'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
            ];

            return $romans[$number - 1];
        }

        function generateNoTransaksi()
        {
            $today = Carbon::now();
            $formattedDate = $today->format('Y') . '/'. convertToRoman($today->format('m')) . '/' . $today->format('d');

            // Cari nomor urut transaksi terakhir pada hari ini
            $lastTransaction = TransaksiPenjualanTitikPenjualan::whereDate('tanggal_transaksi', $today)
                ->latest('created_at') // Mencari yang terakhir
                ->first();

            // Nomor urut transaksi
            $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_transaksi, -4) + 1 : 1;

            // Format nomor transaksi
            $nomorTransaksi = 'TP' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

            return $nomorTransaksi;
        }

        try {
            DB::beginTransaction();
            $data = TransaksiPenjualanTitikPenjualan::create([
                'no_transaksi' => generateNoTransaksi(),
                'toko_id' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->toko_id : 0,
                'member_id' => $request->member ? $request->member : 0,
                'tanggal_transaksi' => Carbon::now(),
                'jumlah_total' => $request->jumlah_total_transaksi,
                'created_by' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->kode : '',
            ]);
            foreach ($request->detail as $row) {
                $detail = ItemPenjualanTitikPenjualan::create([
                    'transaksi_id' => $data['id'],
                    'transaksi_stok_id' => 0,
                    'produk_id' => $row['input_id'],
                    'jumlah' => $row['input_jumlah'],
                    'harga_satuan' => $row['input_harga_satuan'],
                    'harga_total' => $row['input_harga_total'],
                    'created_by' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->kode : '',
                ]);
                
                $stok = TransaksiStok::create([
                    'tanggal' => now(),
                    'gudang_id' => 0,
                    'toko_id' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->toko_id : 0,
                    'produk_id' => $row['input_id'],
                    'metode_transaksi' => 'keluar',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $row['input_jumlah'],
                    'created_by' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->kode : '',
                ]);
                $detail->update(['transaksi_stok_id' => $stok->id]);
            }
            $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::create([
                'transaksi_id' => $data['id'],
                'nominal_pembayaran' => $request->jumlah_total_pembayaran_transaksi,
                'nominal_kembalian' => $request->jumlah_total_kembalian_transaksi,
                'created_by' => auth()->guard('operator_kasir')->user() ? auth()->guard('operator_kasir')->user()->kode : '',
            ]);
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => ['Transaksi' => $data , 'Detail' => $detail]]);
            DB::commit();
            return redirect()->route('kasir.transaksi')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('kasir.transaksi')->with('error', $th->getMessage());
        }
    }

    public function getDataMember(Request $request){
        $data = Member::query();
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDataProduk(Request $request)
    {
        $data = Produk::query()->with('kategori')->with([
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
        ])->where('status', 1)->where('penjualan', 1)->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function history(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }
        
        return view('kasir.transaksi_penjualan.history');
    }

    public function getData(Request $request){
        $data = TransaksiPenjualanTitikPenjualan::query()->with('member')->with('toko')->where('created_by', auth()->guard('operator_kasir')->user()->kode);

        if ($request->status ) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
        }
        $data->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailTransaksi">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        $data = TransaksiPenjualanTitikPenjualan::with('member')->with('toko')->with('item.produk')->with('pembayaran')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }
}
