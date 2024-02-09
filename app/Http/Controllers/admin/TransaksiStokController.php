<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\Gudang;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Http\Controllers\Controller;

class TransaksiStokController extends Controller
{
    private static $module = "transaksi_stok";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.transaksi_stok.index');
    }
    
    public function getData(Request $request){
        $data = TransaksiStok::query()->where('produk_id', $request->produk_id)->where('gudang_id', $request->gudang_id)->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.transaksi_stok.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function getDataProduk(Request $request){
        $data = Produk::query()->where('status', 1)->get();

        return DataTables::of($data)
            ->addColumn('jumlah_stok', function ($row) use ($request) {
                $jumlah = 0;
                    $stok_masuk = TransaksiStok::where('produk_id', $row->id)
                        ->where('gudang_id', $request->gudang)
                        ->whereIn('metode_transaksi', ['masuk'])
                        ->sum('jumlah_unit');

                    // Ambil jumlah stok keluar
                    $stok_keluar = TransaksiStok::where('produk_id', $row->id)
                        ->where('gudang_id', $request->gudang)
                        ->whereIn('metode_transaksi', ['keluar'])
                        ->sum('jumlah_unit');

                $jumlah += $stok_masuk - $stok_keluar;
                return $jumlah;
            })
            ->addColumn('action', function ($row) use ($request){
                $btn = "";
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="'.route('admin.transaksi_stok.detail',['gudang_id' => ($request->gudang ? $request->gudang : 0),'kode'=>$row->kode]).'" class="btn btn-secondary btn-sm me-3 ">
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

        return view('administrator.transaksi_stok.edit',compact('data'));
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
        return redirect()->route('admin.transaksi_stok')->with('success', 'Data berhasil diupdate.');
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

    public function detail($gudang_id, $kode){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Produk::where('kode', $kode)->first();
        $gudang = Gudang::find($gudang_id);

        return view('administrator.transaksi_stok.detail', compact('data', 'gudang'));
    }

    public function getGudang(){
        $gudang = Gudang::where('status', 1)->get();

        return response()->json([
            'gudang' => $gudang,
        ]);
    }
}
