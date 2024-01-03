<?php

namespace App\Http\Controllers\kasir;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\admin\Member;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin\ItemPenjualanTitikPenjualan;
use App\Models\admin\TransaksiPenjualanTitikPenjualan;
use App\Models\admin\PembayaranTransaksiPenjualanTitikPenjualan;

class TransaksiController extends Controller
{
    private static $module = "kasir_transaksi";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "index")) {
            abort(403);
        }

        return view('kasir.transaksi_penjualan.index');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "index")) {
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
                'member_id' => $request->member ? $request->member : 0,
                'tanggal_transaksi' => Carbon::now(),
                'jumlah_total' => $request->jumlah_total_transaksi,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            foreach ($request->detail as $row) {
                $detail = ItemPenjualanTitikPenjualan::create([
                    'transaksi_id' => $data['id'],
                    'produk_id' => $row['input_id'],
                    'jumlah' => $row['input_jumlah'],
                    'harga_satuan' => $row['input_harga_satuan'],
                    'harga_total' => $row['input_harga_total'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
            }
            $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::create([
                'transaksi_id' => $data['id'],
                'nominal_pembayaran' => $request->jumlah_total_pembayaran_transaksi,
                'nominal_kembalian' => $request->jumlah_total_kembalian_transaksi,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
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
            $data = Produk::query()->with('kategori')->where('status',1)->get();

            return DataTables::of($data)
                ->make(true);
        }
}
