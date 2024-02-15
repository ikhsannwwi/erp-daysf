<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;
use App\Models\admin\Member;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use Picqer\Barcode\BarcodeScanner;
use App\Http\Controllers\Controller;
use Picqer\Barcode\BarcodeGeneratorPNG;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\admin\ItemPenjualanTitikPenjualan;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\admin\TransaksiPenjualanTitikPenjualan;
use App\Models\admin\PembayaranTransaksiPenjualanTitikPenjualan;

class TransaksiPenjualanController extends Controller
{
    private static $module = "transaksi_penjualan";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.transaksi_penjualan.index');
    }
    
    public function getData(Request $request){
        $data = TransaksiPenjualanTitikPenjualan::query()->with('member');

        if ($request->status ) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
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
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.transaksi_penjualan.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
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
    
    public function add(){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        return view('administrator.transaksi_penjualan.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
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

                $stok = TransaksiStok::create([
                    'tanggal' => now(),
                    'gudang_id' => 0,
                    'produk_id' => $row['input_id'],
                    'metode_transaksi' => 'masuk',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $row['input_jumlah'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $detail->update(['transaksi_stok_id' => $stok->id]);
            }
            $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::create([
                'transaksi_id' => $data['id'],
                'nominal_pembayaran' => $request->jumlah_total_pembayaran_transaksi,
                'nominal_kembalian' => $request->jumlah_total_kembalian_transaksi,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => ['Transaksi' => $data , 'Detail' => $detail, 'Pembayaran' => $pembayaran]]);
            DB::commit();
            return redirect()->route('admin.transaksi_penjualan')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.transaksi_penjualan')->with('error', $th->getMessage());
        }

        
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = TransaksiPenjualanTitikPenjualan::with('member')->with('item')->with('pembayaran')->find($id);

        return view('administrator.transaksi_penjualan.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $transaksi = TransaksiPenjualanTitikPenjualan::find($id);

        $rules = [
            'jumlah_total_transaksi' => 'required',
            'detail.*.input_id' => 'required',
            'detail.*.input_jumlah' => 'required',
            'detail.*.input_harga_satuan' => 'required',
            'detail.*.input_harga_total' => 'required',
            'jumlah_total_pembayaran_transaksi' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $transaksi->toArray();

        $updates = [
            'member_id' => $request->member ? $request->member : 0,
            'jumlah_total' => $request->jumlah_total_transaksi,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $transaksi->getOriginal());

        try {
            DB::beginTransaction();
            $transaksi->update($updates);
            
            // Tambahkan kembali detail penjualan yang baru
            foreach ($request->detail as $row) {
                if (!empty($row['id'])) {
                    // Jika 'id' ada, maka ini adalah detail yang sudah ada dan perlu diupdate
                    $update_items = [
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $row['input_id'],
                        'jumlah' => $row['input_jumlah'],
                        'harga_satuan' => $row['input_harga_satuan'],
                        'harga_total' => $row['input_harga_total'],
                        'updated_by' => auth()->user() ? auth()->user()->kode : '',
                    ];
    
                    // Temukan detail yang sesuai berdasarkan 'id' dan update
                    ItemPenjualanTitikPenjualan::find($row['id'])->update($update_items);
                } else {
                    // Jika 'id' tidak ada, maka ini adalah detail baru dan perlu dibuat
                    $detail = ItemPenjualanTitikPenjualan::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $row['input_id'],
                        'jumlah' => $row['input_jumlah'],
                        'harga_satuan' => $row['input_harga_satuan'],
                        'harga_total' => $row['input_harga_total'],
                        'created_by' => auth()->user() ? auth()->user()->kode : '',
                    ]);
                }
            }
    
            $pembayaran_update = [
                'nominal_pembayaran' => $request->jumlah_total_pembayaran_transaksi,
                'nominal_kembalian' => $request->jumlah_total_kembalian_transaksi,
                'updated_by' => auth()->user() ? auth()->user()->kode : '',
            ];
    
            $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::where('transaksi_id', $transaksi->id)->first();
            $pembayaran->update($pembayaran_update);
    
            createLog(static::$module, __FUNCTION__, $transaksi->id, ['Data sebelum diupdate' => $previousData, 'Data yang diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.transaksi_penjualan')->with('success', 'Data berhasil diupdate.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.transaksi_penjualan')->with('error', $th->getMessage());
        }
    }
    
    public function updateTotal(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $transaksi = TransaksiPenjualanTitikPenjualan::find($id);
        $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::where('transaksi_id',$id)->first();
        $previousData = $transaksi->toArray();

        $updates = [
            'jumlah_total' => $request->jumlah_total,
        ];

        if (!empty($pembayaran)) {
            $update_kembalian = [
                'nominal_kembalian' => $request->jumlah_total_kembalian_transaksi,
            ];
            $pembayaran->update($update_kembalian);
        }
        $updatedData = array_intersect_key($updates, $transaksi->getOriginal());

        $transaksi->update($updates);
        createLog(static::$module, __FUNCTION__, $transaksi->id, ['Data sebelum diupdate' => $previousData, 'Data yang diupdate' => $updatedData]);
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil'
        ], 200);
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
        $data = TransaksiPenjualanTitikPenjualan::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        // Delete all related details with transaksi_id equal to $data->id
        $details = ItemPenjualanTitikPenjualan::where('transaksi_id', $data->id)->get();
        
        if (!$details->isEmpty()) {
            foreach ($details as $detail) {
                $detail->delete();
            }
        }

        $pembayaran = PembayaranTransaksiPenjualanTitikPenjualan::where('transaksi_id', $data->id)->first();
        if (!empty($pembayaran)) {
            $pembayaran->delete();
        }
        // Delete the transaction
        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }
    
    public function deleteItem(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }
        $id = $request->id;

        // Find the data based on the provided ID.
        $data = ItemPenjualanTitikPenjualan::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        // Delete the transaction
        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = TransaksiPenjualanTitikPenjualan::with('member')->with('item.produk')->with('pembayaran')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function getDataMember(Request $request){
        $data = Member::query();
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }


    public function getMember(){
        $member = Member::all();

        return response()->json([
            'member' => $member,
        ]);
    }

    public function getProduk(){
        $produk = Produk::where('penjualan', 1)->get();

        return response()->json([
            'produk' => $produk,
        ]);
    }

    public function getProductDetails(Request $request) {
        $productIds = $request->input('productIds');
        $productsData = [];
    
        // Lakukan pengolahan data untuk setiap productId
        foreach ($productIds as $productId) {
            // Lakukan operasi pengolahan data sesuai kebutuhan Anda untuk setiap $productId
            $product = Produk::where('id', $productId)->first();
            // Pastikan produk ditemukan sebelum menambahkannya ke dalam array
            if ($product) {
                $productsData[] = $product;
            }
        }
    
        // Kembalikan data dalam format JSON
        return response()->json(['data' => $productsData]);
    }

    public function getDataProduk(Request $request)
    {
        $data = Produk::query()->with('kategori')->where('status', 1)->where('penjualan', 1)->get();

        return DataTables::of($data)
            ->make(true);
    }

    // public function uploadBarcode(Request $request)
    // {
    //     // Memastikan bahwa berkas telah diterima
    //     if ($request->hasFile('barcode') && $request->file('barcode')->isValid()) {
    //         $file = $request->file('barcode');
    //         $imagePath = $file->getPathname();

    //         // Lakukan operasi sesuai dengan berkas yang diunggah
    //         $destinationPath = './administrator/assets/media/barcode/'; // Ganti dengan direktori tujuan yang diinginkan
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $file->move($destinationPath, $fileName);
    //         // dd($imagePath);
    //         // Baca barcode dari berkas gambar yang diunggah
    //         $image = Image::make($imagePath);
    //         $imageBase64 = base64_encode($image->encode('data-url'));
            
    //         // Gunakan DNS1D untuk membaca barcode
    //         $decodedBarcode = DNS1D::decode($imageBase64);

    //         // Mencari data sesuai dengan kode barcode
    //         if ($decodedBarcode) {
    //             $data = Produk::where('kode', $decodedBarcode)->first();

    //             if ($data) {
    //                 return response()->json(['success' => 'File berhasil diunggah.', 'data' => $data]);
    //             } else {
    //                 return response()->json(['error' => 'Data tidak ditemukan untuk barcode yang dipindai.']);
    //             }
    //         } else {
    //             return response()->json(['error' => 'Barcode tidak ditemukan dalam gambar yang diunggah.']);
    //         }
    //     } else {
    //         return response()->json(['error' => 'Berkas tidak diterima.']);
    //     }
    // }

    // public function uploadBarcode(Request $request)
    // {
    //     // Validate the incoming request
    //     $request->validate([
    //         'barcode' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     // Get the file from the request
    //     $barcodeImage = $request->file('barcode');

    //     // Process the barcode image to get the barcode value (you may use a barcode library)
    //     $barcodeValue = $this->processBarcodeImage($barcodeImage);
    //     dd($barcodeValue);

    //     // Find the product in the model using the barcode value
    //     $product = Produk::where('kode', $barcodeValue)->first();

    //     if ($product) {
    //         // Product found, you can now perform further actions
    //         return response()->json(['success' => true, 'product' => $product]);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Product not found']);
    //     }
    // }

    // public function uploadBarcode(Request $request)
    // {
    //     $destinationPath = './administrator/assets/media/barcode/';

    //     // Validate the incoming request
    //     $request->validate([
    //         'barcode' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     // Get the file from the request
    //     $barcodeImage = $request->file('barcode');

    //     // Check if the image is a valid file
    //     if ($barcodeImage->isValid()) {
    //         // Generate a unique filename for the uploaded file
    //         $fileName = time() . '_' . $barcodeImage->getClientOriginalName();

    //         // Move the file to the specified destination path
    //         $barcodeImage->move($destinationPath, $fileName);

    //         // Create a BarcodeGenerator instance
    //         $generator = new BarcodeGeneratorPNG();

    //         // Get the base64-encoded PNG image of the barcode
    //         $barcodeBase64 = $generator->getBarcode(asset('administrator/assets/media/barcode/'.$fileName), $generator::TYPE_CODE_128);

    //         // Extract the barcode value from the base64 image data (you might need to adjust this part)
    //         $barcodeValue = $this->extractBarcodeValueFromBase64($barcodeBase64);
    //     dd($barcodeBase64);

    //         // Now you can use $barcodeValue or perform any other actions
    //         $produk = Produk::where('kode', $barcodeValue)->first();


    //         // You might also want to return a response or redirect
    //         return response()->json(['success' => true, 'produk' => $produk]);
    //     } else {
    //         // If the image is not valid, return an error response
    //         return response()->json(['success' => false, 'message' => 'Invalid image']);
    //     }

    // }

    // private function extractBarcodeValueFromBase64($base64Data)
    // {
    //     // Decode base64 data
    //     $decodedData = base64_decode($base64Data);

    //     // Check if decoding is successful
    //     if ($decodedData === false) {
    //         // Handle decoding error
    //         return null; // atau sesuaikan dengan cara Anda mengelola kesalahan
    //     }

    //     // Extract barcode value from the decoded data
    //     $barcodeValue = $decodedData; // Gantilah dengan logika ekstraksi yang sesuai

    //     return $barcodeValue;
    // }

    

    // private function extractBarcodeValueFromImage($imagePath)
    // {
    //     $tesseract = new TesseractOCR(asset('administrator/assets/media/barcode/'.$imagePath));
    //     $tesseract->setWhitelist(range('0', '9')); // Specify that you expect numeric digits

    //     return $tesseract->run();
    // }

    // public function uploadBarcode(Request $request)
    // {
    //     $destinationPath = './administrator/assets/media/barcode/';

    //     // Validate the incoming request
    //     $request->validate([
    //         'barcode' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     // Get the file from the request
    //     $barcodeImage = $request->file('barcode');

    //     // Check if the image is a valid file
    //     if ($barcodeImage->isValid()) {
    //         // Generate a unique filename for the uploaded file
    //         $fileName = time() . '_' . $barcodeImage->getClientOriginalName();

    //         // Move the file to the specified destination path
    //         $barcodeImage->move($destinationPath, $fileName);

    //         // Extract the barcode value using Tesseract OCR
    //         $barcodeValue = $this->extractBarcodeValueFromImage($fileName);

    //         // Now you can use $barcodeValue or perform any other actions
    //         $produk = Produk::where('kode', $barcodeValue)->first();

    //         // You might also want to return a response or redirect
    //         return response()->json(['success' => true, 'produk' => $produk]);
    //     } else {
    //         // If the image is not valid, return an error response
    //         return response()->json(['success' => false, 'message' => 'Invalid image']);
    //     }
    // }

}
