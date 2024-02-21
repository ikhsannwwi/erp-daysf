<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Toko;
use App\Models\Karyawan;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\StokOpnameToko;
use App\Http\Controllers\Controller;
use App\Models\StokOpnameTokoDetail;

class StokOpnameTokoController extends Controller
{
    private static $module = "stok_opname_toko";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.stok_opname_toko.index');
    }
    
    public function getData(Request $request){
        $data = StokOpnameToko::query()->with('toko')->with('karyawan');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailStokOpnameToko">
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

        return view('administrator.stok_opname_toko.add');
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
        $lastTransaction = StokOpnameToko::whereDate('tanggal', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_stok_opname, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'SOT' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

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
            'toko' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $data = StokOpnameToko::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'toko_id' => $request->toko,
                'karyawan_id' => $request->karyawan,
                'no_stok_opname' => $this->generateNomorStokOpname(),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            foreach ($request->detail as $row) {
                // dd($row);
                $detail = StokOpnameTokoDetail::create([
                    'stok_opname_toko_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'jumlah_stok_fisik' => str_replace(['.', ','], '', $row['jumlah_stok_fisik']),
                    'selisih' => str_replace(['.', ','], '', $row['selisih']),
                    'keterangan' => $row['keterangan'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.stok_opname_toko')->with('success', 'Data berhasil disimpan.');
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

        $data = StokOpnameToko::findOrFail($id);
        $detail = StokOpnameTokoDetail::where('stok_opname_toko_id', $id)->get();

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        try {
            DB::beginTransaction();
            foreach ($detail as $row) {
                $row->delete();
            }
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);
            
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
        $data = StokOpnameTokoDetail::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        // Delete the transaction
        try {
            DB::beginTransaction();
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);
            
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

        $data = StokOpnameToko::with([
            'detail' => function ($query) {
                $query->with([
                    'produk' => function($query_produk){
                        $query_produk->with('satuan');
                    }
                ]);
            },
            'toko', 'karyawan'
        ])->find($id);
        

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function getDataToko(Request $request){
        $data = Toko::query();
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
            ->where('toko_id', $request->toko)
            ->whereIn('metode_transaksi', ['masuk']);

        if (!empty($request->created_at)) {
            $stok_masuk->where('created_at', '<', $request->created_at);
        }

        $stok_masuk = $stok_masuk->sum('jumlah_unit');

        // Ambil jumlah stok keluar
        $stok_keluar = TransaksiStok::where('produk_id', $request->produk)
            ->where('toko_id', $request->toko)
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
