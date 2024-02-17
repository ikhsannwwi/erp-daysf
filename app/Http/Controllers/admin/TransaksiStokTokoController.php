<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Toko;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Http\Controllers\Controller;

class TransaksiStokTokoController extends Controller
{
    private static $module = "transaksi_stok_toko";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.transaksi_stok_toko.index');
    }
    
    public function getData(Request $request){
        $data = TransaksiStok::query()->where('produk_id', $request->produk_id)->where('toko_id', $request->toko_id)->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDataProduk(Request $request){
        $data = Produk::query()->where('status', 1)->get();

        return DataTables::of($data)
            ->addColumn('jumlah_stok', function ($row) use ($request) {
                $jumlah = 0;
                    $stok_masuk = TransaksiStok::where('produk_id', $row->id)
                        ->where('toko_id', $request->toko)
                        ->whereIn('metode_transaksi', ['masuk'])
                        ->sum('jumlah_unit');

                    // Ambil jumlah stok keluar
                    $stok_keluar = TransaksiStok::where('produk_id', $row->id)
                        ->where('toko_id', $request->toko)
                        ->whereIn('metode_transaksi', ['keluar'])
                        ->sum('jumlah_unit');

                $jumlah += $stok_masuk - $stok_keluar;
                return $jumlah;
            })
            ->addColumn('action', function ($row) use ($request){
                $btn = "";
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="'.route('admin.transaksi_stok_toko.detail',['toko_id' => ($request->toko ? $request->toko : 0),'kode'=>$row->kode]).'" class="btn btn-secondary btn-sm me-3 ">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['jumlah_stok','action'])
            ->make(true);
    }
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = TransaksiStok::find($id);

        return view('administrator.transaksi_stok_toko.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = TransaksiStok::find($id);

        $rules = [
            'nama' => 'required',
            'telepon' => 'required',
            'email' => 'required',
            'alamat' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.transaksi_stok_toko')->with('success', 'Data berhasil diupdate.');
    }

    public function delete(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }

        // Ensure you have authorization mechanisms here before proceeding to delete data.

        $id = $request->id;

        // Find the data based on the provided ID.
        $data = TransaksiStok::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $data->toArray();

        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna telah dihapus.',
        ]);
    }

    public function detail($toko_id, $kode){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Produk::where('kode', $kode)->first();
        $toko = Toko::find($toko_id);

        return view('administrator.transaksi_stok_toko.detail', compact('data', 'toko'));
    }

    public function getToko(){
        $toko = Toko::where('status', 1)->get();

        return response()->json([
            'toko' => $toko,
        ]);
    }
}
