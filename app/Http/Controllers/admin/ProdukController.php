<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\admin\Kategori;
use App\Http\Controllers\Controller;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ProdukController extends Controller
{
    private static $module = "produk";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.produk.index');
    }
    
    public function getData(Request $request){
        $data = Produk::query()->with('kategori');

        if ($request->status || $request->kategori) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->kategori != "") {
                $kategori_id = $request->kategori ;
                $data->where("kategori_id", $kategori_id);
            }
            $data->get();
        }

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                if (isAllowed(static::$module, "status")) : //Check permission
                    if ($row->status) {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status" checked="checked" />
                        <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                    </div>';
                        $status .= '<span class="badge bg-success">Aktif</span></div>';
                    } else {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status"/>
                            <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                            </div>';
                        $status .= '<span class="badge bg-danger">Tidak Aktif</span></div>';
                    }
                    return $status;
                endif;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.produk.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailProduk">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function add(){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        return view('administrator.produk.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        
        $request->validate([
            'kategori' => 'required',
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'status' => 'required',
        ]);

        // Fungsi untuk menghasilkan nomor produk
        function generateKodeProduk($length = 8) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        // Menghasilkan Kode produk
        $KodeProduk = generateKodeProduk(8); // Misalnya, panjang Kode produk adalah 8 karakter

        // Generate barcode
        $barcodeGenerator = new BarcodeGeneratorHTML();
        $barcode = $barcodeGenerator->getBarcode($KodeProduk, $barcodeGenerator::TYPE_CODE_128);
    

        // Ambil nilai harga dari permintaan
        $harga = $request->harga;
        
        // Hapus 'Rp' dan karakter pemisah ribuan dari nilai harga
        $harga = str_replace('Rp ', '', $harga);
        $harga = str_replace('.', '', $harga);
        // dd($harga);


        $data = Produk::create([
            'kategori_id' => $request->kategori,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $harga,
            'kode' => $KodeProduk,
            'barcode' => $barcode,
            'status' => $request->status,
            'pembelian' => $request->pembelian ? $request->pembelian : 0,
            'formula' => $request->formula ? $request->formula : 0,
            'produksi' => $request->produksi ? $request->produksi : 0,
            'penjualan' => $request->penjualan ? $request->penjualan : 0,
            'created_by' => auth()->user() ? auth()->user()->kode : '',
        ]);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
        return redirect()->route('admin.produk')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Produk::find($id);

        return view('administrator.produk.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Produk::find($id);

        $rules = [
            'kategori' => 'required',
            'nama' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        // Ambil nilai harga dari permintaan
        $harga = $request->harga;
        
        // Hapus 'Rp' dan karakter pemisah ribuan dari nilai harga
        $harga = str_replace('Rp ', '', $harga);
        $harga = str_replace('.', '', $harga);

        $updates = [
            'kategori_id' => $request->kategori,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $harga,
            'status' => $request->status,
            'pembelian' => $request->pembelian ? $request->pembelian : 0,
            'formula' => $request->formula ? $request->formula : 0,
            'produksi' => $request->produksi ? $request->produksi : 0,
            'penjualan' => $request->penjualan ? $request->penjualan : 0,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.produk')->with('success', 'Data berhasil diupdate.');
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
        $data = Produk::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $data->toArray();

        // Delete the data.
        $data->update([
            'deleted_by' => auth()->user() ? auth()->user()->kode : '',
        ]);
        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna telah dihapus.',
        ]);
    }

    
    
    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Produk::with('kategori')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function changeStatus(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "status")) {
            abort(403); 
        }
        
        $data['status'] = $request->status == "Aktif" ? 1 : 0;
        $log = $request->status == 1 ? 'Aktif' : 'Tidak Aktif';
        $id = $request->ix;
        $updates = Produk::where(["id" => $id])->first();
        // Simpan data sebelum diupdate
        $previousData = $updates->toArray();
        $updates->update($data);

        //Write log
        createLog(static::$module, __FUNCTION__, $id, ['Data' => $previousData,'Statusnya diubah menjadi' => $log]);
        return response()->json([
            'status' => 'success',
            'message' => 'Status telah diubah.',
        ]);
    }
    
    public function getKategori(){
        $kategori = Kategori::all();

        return response()->json([
            'kategori' => $kategori,
        ]);
    }
    
    public function checkNama(Request $request){
        if($request->ajax()){
            $users = Produk::where('nama', $request->nama)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Nama sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }

    public function arsip(){
        //Check permission
        if (!isAllowed(static::$module, "arsip")) {
            abort(403);
        }

        return view('administrator.produk.arsip');
    }

    public function getDataArsip(Request $request){
        $data = Produk::query()->with('kategori')->onlyTrashed();

        if ($request->status || $request->kategori) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->kategori != "") {
                $kategori_id = $request->kategori ;
                $data->where("kategori_id", $kategori_id);
            }
            $data->get();
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "restore")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-primary restore btn-sm me-3 ">
                    Restore
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function restore(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "restore")) {
            abort(403);
        }
        
        $id = $request->id;
        $data = Produk::withTrashed()->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $data->restore();

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dipulihkan' => $data]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dipulihkan.'
        ]);
    }


    public function forceDelete(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }
        
        $id = $request->id;

        $data = Produk::withTrashed()->find($id);

        if (!$data) {
            return redirect()->route('admin.produk.arsip')->with('error', 'Data tidak ditemukan.');
        }

        $data->forceDelete();

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, $data);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus secara permanent.',
        ]);
    }
}
