<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Gudang;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\PenyesuaianStok;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PenyesuaianStokController extends Controller
{
    private static $module = "penyesuaian_stok";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.penyesuaian_stok.index');
    }
    
    public function getData(Request $request){
        $data = PenyesuaianStok::query()->with('gudang')->with('produk')->where('toko_id', 0)->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.penyesuaian_stok.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailPenyesuaianStok">
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

        return view('administrator.penyesuaian_stok.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        
        $rules = [
            'tanggal' => 'required',
            'gudang' => 'required',
            'produk' => 'required',
            'metode' => 'required|in:masuk,keluar',
            'jumlah' => 'required',
        ];

        $request->validate($rules);

        $jumlah = 0;
        $stok_masuk = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['masuk'])
            ->sum('jumlah_unit');

        // Ambil jumlah stok keluar
        $stok_keluar = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['keluar'])
            ->sum('jumlah_unit');

        $jumlah += $stok_masuk - $stok_keluar;
        if ($request->metode === 'keluar') {
            if ($jumlah < 0) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
        }
        

        try {
            DB::beginTransaction();
            $data = PenyesuaianStok::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'gudang_id' => $request->gudang,
                'produk_id' => $request->produk,
                'metode_transaksi' => $request->metode,
                'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah),
                'keterangan' => $request->keterangan,
                'transaksi_stok_id' => 0,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            $transaksi = TransaksiStok::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'gudang_id' => $request->gudang,
                'produk_id' => $request->produk,
                'metode_transaksi' => $request->metode,
                'jenis_transaksi' => static::$module,
                'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah),
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            $data->update(['transaksi_stok_id' => $transaksi->id]);
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.penyesuaian_stok')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = PenyesuaianStok::find($id);

        return view('administrator.penyesuaian_stok.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = PenyesuaianStok::find($id);
        $transaksi_stok = TransaksiStok::find($data->transaksi_stok_id);

        $rules = [
            'tanggal' => 'required',
            'gudang' => 'required',
            'produk' => 'required',
            'metode' => 'required|in:masuk,keluar',
            'jumlah' => 'required',
        ];

        $request->validate($rules);

        $previousData = [
            'penyesuaian_stok' => $data->toArray(),
            'transaksi_stok' => $transaksi_stok->toArray()
        ];

        $updates = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'metode_transaksi' => $request->metode,
            'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah),
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        $update_stok = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'metode_transaksi' => $request->metode,
            'jenis_transaksi' => static::$module,
            'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah),
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];
        
        $updatedData = [
            'penyesuaian_stok' => array_intersect_key($updates, $data->getOriginal()),
            'transaksi_stok' => array_intersect_key($update_stok, $transaksi_stok->getOriginal())
        ];

        try {
            DB::beginTransaction();
            $data->update($updates);
            $transaksi_stok->update($update_stok);
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.penyesuaian_stok')->with('success', 'Data berhasil diupdate.');
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

        $data = PenyesuaianStok::findOrFail($id);

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        $stok = TransaksiStok::find($data->transaksi_stok_id);
        if ($stok) {
            $stok->delete();
        }

        $data->delete();

        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = PenyesuaianStok::with('gudang')->with('produk')->find($id);

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

    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori');
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }

    public function checkStock(Request $request){
        $jumlah = 0;
        $stok_masuk = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['masuk'])
            ->sum('jumlah_unit');

        // Ambil jumlah stok keluar
        $stok_keluar = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang)
            ->whereIn('metode_transaksi', ['keluar'])
            ->sum('jumlah_unit');

        $jumlah += $stok_masuk - $stok_keluar;
        if ($request->metode === 'keluar') {
            if ($jumlah < 0 ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi',
                    'valid' => false
                ]);
            }else if($jumlah < intVal(str_replace(['.',','], '', $request->jumlah))){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi',
                    'valid' => false
                ]);
            }else {
                return response()->json([
                    'message' => '1',
                    'valid' => true
                ]);
            }
        }else {
            return response()->json([
                'message' => '2',
                'valid' => true
            ]);
        }
    }
}
