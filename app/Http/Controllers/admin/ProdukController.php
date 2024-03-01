<?php

namespace App\Http\Controllers\admin;

use DB;
use File;
use PDF;
use DataTables;
use App\Models\Satuan;
use App\Models\ProdukImage;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\admin\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
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
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete mx-2 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.produk.edit',$row->id).'" class="btn btn-primary btn-sm mx-2 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#detailProduk">
                    Detail
                    </a>';
                endif;
                if (isAllowed(static::$module, "cetak")) : //Check permission
                    $btn .= '<a href="'.route('admin.produk.cetak',$row->kode).'" class="btn btn-secondary btn-sm mx-2 " target="_blank" title="Cetak Barcode">
                    <i class="bi bi-printer-fill"></i>
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
            'satuan' => 'required',
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

        try {
            DB::beginTransaction();
            $data = Produk::create([
                'kategori_id' => $request->kategori,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'satuan_id' => $request->satuan,
                'harga' => $harga,
                'kode' => $KodeProduk,
                'barcode' => $barcode,
                'status' => $request->status,
                'pembelian' => $request->pembelian ? $request->pembelian : 0,
                'formula' => $request->formula ? $request->formula : 0,
                'produksi' => $request->produksi ? $request->produksi : 0,
                'penjualan' => $request->penjualan ? $request->penjualan : 0,
                'e_commerce' => $request->e_commerce ? $request->e_commerce : 0,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
    
            if ($request->hasFile('img')) {
                $no = 0;
                foreach ($request->file('img') as $image) {
                    // Crop gambar
                    $croppedImage = Image::make($image->getRealPath())
                    ->crop(
                        ($request->dataImage[$no]['width'] !== null ? $request->dataImage[$no]['width'] : 720),
                        ($request->dataImage[$no]['height'] !== null ? $request->dataImage[$no]['height'] : 1080),
                        ($request->dataImage[$no]['x'] !== null ? $request->dataImage[$no]['x'] : 488),
                        ($request->dataImage[$no]['y'] !== null ? $request->dataImage[$no]['y'] : 0)
                    );
    
                    // Kompres gambar dengan kualitas tertentu (contoh: 80%)
                    $compressedImage = $croppedImage->encode('jpg', 80);
    
                    // Simpan gambar hasil cropping dan kompresi
                    $fileName = $data->kode . '_' . $no . '_' . date('Y-m-d-H-i-s') . '_' . uniqid(2) . '.jpg';
                    $path = upload_path('produk') . $fileName;
                    $compressedImage->save($path);
    
                    // Simpan data gambar ke database
                    $imageModel = ProdukImage::create([
                        'produk_id' => $data->id,
                        'image' => $fileName,
                    ]);

                    $no++;
                }
            }
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.produk')->with('success', 'Data berhasil disimpan.');
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

        $data = Produk::with('satuan')->with('image')->find($id);

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
            'satuan' => 'required',
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

        try {
            DB::beginTransaction();
            $updates = [
                'kategori_id' => $request->kategori,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'satuan_id' => $request->satuan,
                'harga' => $harga,
                'status' => $request->status,
                'pembelian' => $request->pembelian ? $request->pembelian : 0,
                'formula' => $request->formula ? $request->formula : 0,
                'produksi' => $request->produksi ? $request->produksi : 0,
                'penjualan' => $request->penjualan ? $request->penjualan : 0,
                'e_commerce' => $request->e_commerce ? $request->e_commerce : 0,
                'updated_by' => auth()->user() ? auth()->user()->kode : '',
            ];

            foreach ($request->dataImage as $key => $row) {
                if (!empty($row['id'])) {
                    $image = ProdukImage::find($row['id']);
                    $image_path = "./administrator/assets/media/produk/" . $image->image;
                    if (File::exists($image_path)) {
                        if (($row['width'] !== null) || ($row['height'] !== null) || ($row['x'] !== null) || ($row['y'] !== null) ) {
                            $croppedImage = Image::make($image_path)
                                ->crop(
                                    intVal($row['width']),
                                    intVal($row['height']),
                                    intVal($row['x']),
                                    intVal($row['y'])
                                );
        
                            // Simpan gambar hasil cropping dan kompresi
                            $path = upload_path('produk') . $image->image;
                            $croppedImage->save($path);
                        }
                    }
                    
                }
            }

            if ($request->hasFile('img')) {
                $no = 0;
                foreach ($request->file('img') as $image) {
                    // Crop gambar
                    $croppedImage = Image::make($image->getRealPath())
                    ->crop(
                        ($request->dataImage[$no]['width'] !== null ? $request->dataImage[$no]['width'] : 720),
                        ($request->dataImage[$no]['height'] !== null ? $request->dataImage[$no]['height'] : 1080),
                        ($request->dataImage[$no]['x'] !== null ? $request->dataImage[$no]['x'] : 488),
                        ($request->dataImage[$no]['y'] !== null ? $request->dataImage[$no]['y'] : 0)
                    );
    
                    // Kompres gambar dengan kualitas tertentu (contoh: 80%)
                    $compressedImage = $croppedImage->encode('jpg', 80);
    
                    // Simpan gambar hasil cropping dan kompresi
                    $fileName = $data->kode . '_' . $no . '_' . date('Y-m-d-H-i-s') . '_' . uniqid(2) . '.jpg';
                    $path = upload_path('produk') . $fileName;
                    $compressedImage->save($path);
    
                    // Simpan data gambar ke database
                    $imageModel = ProdukImage::create([
                        'produk_id' => $data->id,
                        'image' => $fileName,
                    ]);

                    $no++;
                }
            }
    
            // Filter only the updated data
            $updatedData = array_intersect_key($updates, $data->getOriginal());
    
            $data->update($updates);
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.produk')->with('success', 'Data berhasil diupdate.');
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

        // Ensure you have authorization mechanisms here before proceeding to delete data.

        $id = $request->id;

        // Find the data based on the provided ID.
        $data = Produk::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
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
            'message' => 'Data telah dihapus.',
        ]);
    }

    public function deleteImage(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }
        $id = $request->id;

        $data = ProdukImage::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();
        $image_path = "./administrator/assets/media/produk/" . $data->image;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $data->delete();

        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }

    public function cetak($kode)
    {
        ini_set('max_execution_time', 600); // Set the maximum execution time to 600 seconds (5 minutes)

        $data = Produk::where('kode', $kode)->first();

        // Render the view using Laravel's View class
        $html = View::make('administrator.produk.cetak', compact('data'))->render();

        // Configure PDF settings (optional)
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper([0, 0, 300, 120]);
        // $pdf->setPaper('legal', 'landscape');

        // Output the PDF (open in browser)
        try {
            return $pdf->stream('barcode-produk.pdf');
        } catch (\Exception $e) {
            return $e->getMessage(); // Output any error message to help diagnose the problem
        }
    }

    public function getDataSatuan(Request $request){
        $data = Satuan::query();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Produk::with('satuan')->with('kategori')->find($id);

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
        $image = ProdukImage::where('produk_id', $id)->get();

        if (!$data) {
            return redirect()->route('admin.produk.arsip')->with('error', 'Data tidak ditemukan.');
        }

        foreach ($image as $key => $row) {
            $image_path = "./administrator/assets/media/produk/" . $row->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $row->delete();
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
