<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\Karyawan;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\SatuanKonversi;
use App\Models\StokOpnameGudang;
use App\Http\Controllers\Controller;
use App\Models\StokOpnameGudangDetail;

class StokOpnameGudangController extends Controller
{
    private static $module = "stok_opname_gudang";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.stok_opname_gudang.index');
    }
    
    public function getData(Request $request){
        $data = StokOpnameGudang::query()->with('gudang')->with('karyawan');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailStokOpnameGudang">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function add(){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        return view('administrator.stok_opname_gudang.add');
    }

    
    function convertToRoman($number)
    {
        $romans = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romans[$number - 1];
    }

    function generateNomorStokOpname(){
        $today = Carbon::now();
        $formattedDate = $today->format('Y') . '/'. $this->convertToRoman($today->format('m')) . '/' . $today->format('d');

        // Cari nomor urut transaksi terakhir pada hari ini
        $lastTransaction = StokOpnameGudang::whereDate('tanggal', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_stok_opname, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'SOG' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        return $nomorTransaksi;
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        // dd($request);
        $rules = [
            'tanggal' => 'required',
            'karyawan' => 'required',
            'gudang' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        $log = [];

        try {
            DB::beginTransaction();
            $data = StokOpnameGudang::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'gudang_id' => $request->gudang,
                'karyawan_id' => $request->karyawan,
                'no_stok_opname' => $this->generateNomorStokOpname(),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            $log[] = $data->toArray();
            
            foreach ($request->detail as $row) {
                // dd($row);
                $detail = StokOpnameGudangDetail::create([
                    'stok_opname_gudang_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'jumlah_stok_fisik' => str_replace(['.', ','], '', $row['jumlah_stok_fisik']),
                    'selisih' => str_replace(['.', ','], '', $row['selisih']),
                    'keterangan' => $row['keterangan'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $log['detail'][] = $detail->toArray();
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $log]);
            DB::commit();
            return redirect()->route('admin.stok_opname_gudang')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }
    }
    
    public function delete(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }

        $id = $request->id;

        $data = StokOpnameGudang::findOrFail($id);
        $detail = StokOpnameGudangDetail::where('stok_opname_gudang_id', $id)->get();

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $log = [];
        $log[] = $data->toArray();
        
        try {
            DB::beginTransaction();
            foreach ($detail as $row) {
                $log['detail'][] = $row->toArray();
                $row->delete();
            }
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $log]);
            
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Data telah dihapus.',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Error : ' .$th->getMessage(),
            ], 500);
        }
    }

    public function deleteDetail(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }
        $id = $request->id;

        // Find the data based on the provided ID.
        $data = StokOpnameGudangDetail::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $log = $data->toArray();

        // Delete the transaction
        try {
            DB::beginTransaction();
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $log]);
            
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Data telah dihapus.',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Error : ' .$th->getMessage(),
            ], 500);
        }
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = StokOpnameGudang::with([
            'detail' => function ($query) {
                $query->with([
                    'produk' => function($query_produk){
                        $query_produk->with('satuan');
                    }
                ]);
            },
            'gudang', 'karyawan'
        ])->find($id);
        

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function getDataGudang(Request $request){
        $data = Gudang::query();
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }

    public function getDataKaryawan(Request $request){
        $data = Karyawan::query();
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori')->with('satuan');
        $data->where("status", 1)->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDataStok(Request $request)
    {
        $jumlah = 0;

        // Ambil jumlah stok masuk
        $stok_masuk = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['masuk']);

        if (!empty($request->created_at)) {
            $stok_masuk->where('created_at', '<', $request->created_at);
        }

        $stok_masuk = $stok_masuk->sum('jumlah_unit');

        // Ambil jumlah stok keluar
        $stok_keluar = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['keluar']);

        if (!empty($request->created_at)) {
            $stok_keluar->where('created_at', '<', $request->created_at);
        }

        $stok_keluar = $stok_keluar->sum('jumlah_unit');

        $jumlah += $stok_masuk - $stok_keluar;

        return response()->json([
            'data' => $jumlah,
            'status' => 'success',
            'message' => 'Sukses memuat data.',
        ]);
    }

}
