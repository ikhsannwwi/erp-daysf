<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\Pembelian;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\admin\Supplier;
use App\Models\SatuanKonversi;
use App\Models\PembelianDetail;
use App\Http\Controllers\Controller;

class PembelianController extends Controller
{
    private static $module = "pembelian";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.pembelian.index');
    }
    
    public function getData(Request $request){
        $data = Pembelian::query()->with('supplier');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.pembelian.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailPembelian">
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

        return view('administrator.pembelian.add');
    }

    
    function convertToRoman($number)
    {
        $romans = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romans[$number - 1];
    }

    function generateNomorPembelian(){
        $today = Carbon::now();
        $formattedDate = $today->format('Y') . '/'. $this->convertToRoman($today->format('m')) . '/' . $today->format('d');

        // Cari nomor urut transaksi terakhir pada hari ini
        $lastTransaction = Pembelian::whereDate('tanggal', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_pembelian, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'PO' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

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
            'supplier' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $data = Pembelian::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'supplier_id' => $request->supplier,
                'no_pembelian' => $this->generateNomorPembelian(),
                'total' => str_replace(['.', ','], '', $request->total),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            $log_detail = [];
            
            foreach ($request->detail as $row) {
                // dd($row);
                $pembelian_detail = PembelianDetail::create([
                    'pembelian_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'gudang_id' => $row['gudang'],
                    'satuan_id' => $row['satuan'],
                    'transaksi_stok_id' => 0,
                    'harga_satuan' => str_replace(['Rp ', '.'], '', $row['harga_satuan']),
                    'sub_total' => str_replace(['Rp ', '.'], '', $row['sub_total']),
                    'jumlah_unit' => str_replace(['.', ','], '', $row['jumlah_unit']),
                    'keterangan' => $row['keterangan'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);

                if ($row['satuan'] === 0 || $row['satuan'] === "0") {
                    $kuantitas = str_replace(['.', ','], '', $row['jumlah_unit']);
                } else {
                    $satuan_konversi = SatuanKonversi::find($row['satuan']);
                    $kuantitas = $satuan_konversi->kuantitas_satuan * str_replace(['.', ','], '', $row['jumlah_unit']);
                }
                
                $stok = TransaksiStok::create([
                    'tanggal' => date('Y-m-d', strtotime($data->tanggal)),
                    'gudang_id' => $row['gudang'],
                    'produk_id' => $row['produk'],
                    'metode_transaksi' => 'masuk',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $kuantitas,
                    'keterangan' => $row['keterangan'],
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $pembelian_detail->update(['transaksi_stok_id' => $stok->id]);

                $log_detail[] = [
                    $pembelian_detail,
                    'Transaksi Stok' => $stok
                ];
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => ['Master' => $data, 'Detail' => $log_detail]]);
            DB::commit();
            return redirect()->route('admin.pembelian')->with('success', 'Data berhasil disimpan.');
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

        $data = Pembelian::with('detail')->with('supplier')->find($id);

        return view('administrator.pembelian.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Pembelian::find($id);
        
        $rules = [
            'tanggal' => 'required',
            'supplier' => 'required',
            'detail' => 'required',
        ];
        
        $request->validate($rules);
        // dd($request);

        $previousData = [
            'pembelian' => $data->toArray(),
            'detail' => []
        ];

        $updates = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'supplier_id' => $request->supplier,
            'total' => str_replace(['.', ','], '', $request->total),
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];
        
        try {
            DB::beginTransaction();
            $data->update($updates);
            
            $updatedData = [
                'pembelian' => array_intersect_key($updates, $data->getOriginal()),
                'detail' => []
            ];
            
            foreach ($request->detail as $row) {
                $commonFields = [
                    'produk_id' => $row['produk'],
                    'gudang_id' => $row['gudang'],
                ];
                
                $detail_updates = array_merge($commonFields, [
                    'pembelian_id' => $data->id,
                    'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                    'satuan_id' => $row['satuan'],
                    'keterangan' => $row['keterangan'],
                    'harga_satuan' => str_replace(['Rp ', '.'], '', $row['harga_satuan']),
                    'sub_total' => str_replace(['Rp ', '.'], '', $row['sub_total']),
                    'jumlah_unit' => str_replace(['.', ','], '', $row['jumlah_unit']),
                    'transaksi_stok_id' => !empty($row['id']) ? $row['transaksi_stok_id'] : 0,
                    'created_by' => !empty($row['id']) ? auth()->user()->kode : '',
                    'updated_by' => empty($row['id']) ? auth()->user()->kode : '',
                ]);

                if ($row['satuan'] === 0 || $row['satuan'] === "0") {
                    $kuantitas = str_replace(['.', ','], '', $row['jumlah_unit']);
                } else {
                    $satuan_konversi = SatuanKonversi::find($row['satuan']);
                    $kuantitas = $satuan_konversi->kuantitas_satuan * str_replace(['.', ','], '', $row['jumlah_unit']);
                }
    
                $update_stok = array_merge($commonFields, [
                    'tanggal' => date('Y-m-d', strtotime($data->tanggal)),
                    'metode_transaksi' => 'masuk',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $kuantitas,
                    'created_by' => !empty($row['id']) ? auth()->user()->kode : '',
                    'updated_by' => empty($row['id']) ? auth()->user()->kode : '',
                ]);
    
                if (!empty($row['id'])) {
                    $detail = PembelianDetail::find($row['id']);
                    $transaksi_stok = TransaksiStok::find($row['transaksi_stok_id']);
                    
                    $previousData['detail'][] = $detail->toArray();
                    $previousData['detail'][] = ['Transaksi Stok' =>$transaksi_stok->toArray()];
                    
                    $detail->update($detail_updates);
                    $transaksi_stok->update($update_stok);

                    $updatedData['detail'][] = array_intersect_key($detail_updates, $detail->getOriginal());
                    $updatedData['detail'][] = ['Transaksi Stok' => array_intersect_key($update_stok, $transaksi_stok->getOriginal())];
                } else {
                    $detail = PembelianDetail::create($detail_updates);
                    $transaksi_stok = TransaksiStok::create($update_stok);
                    $detail->update(['transaksi_stok_id' => $transaksi_stok->id]);

                    $previousData['detail'][] = $detail->toArray();
                    $previousData['detail'][] = ['Transaksi Stok' =>$transaksi_stok->toArray()];
                }
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.pembelian')->with('success', 'Data berhasil diupdate.');
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

        $data = Pembelian::findOrFail($id);
        $detail = PembelianDetail::where('pembelian_id', $id)->get();

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = $data->toArray();

        try {
            DB::beginTransaction();
            $log_detail = [];
            foreach ($detail as $row) {
                $log_detail[] = $row->toArray();
                $stok = TransaksiStok::find($row->transaksi_stok_id);
                if ($stok) {
                    $log_detail['Transaksi Stok'][] = $stok->toArray();
                    $stok->delete();
                }
                $row->delete();
            }
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => ['Master' => $deletedData, 'Detail' => $log_detail]]);
            
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
        $data = PembelianDetail::findorfail($id);

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
            $stok = TransaksiStok::find($data->transaksi_stok_id);
            if ($stok) {
                $log_stok = $stok->toArray();
                $stok->delete();
            }
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => ['Data' => $deletedData, 'Transaksi Stok' => $log_stok]]);
            
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

    public function updateTotal(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Pembelian::find($id);
        $previousData = $data->toArray();

        $updates = [
            'total' => $request->total,
        ];

        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);
        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data yang diupdate' => $updatedData]);
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil'
        ], 200);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Pembelian::with([
            'detail' => function ($query) {
                $query->with(['satuan_konversi', 'gudang',
                    'produk' => function($produk_query){
                        $produk_query->with('satuan');
                    }
                ]);
            },
            'supplier'
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

    public function getDataSatuan(Request $request) {
        $produk = Produk::find($request->produk_id);
    
        $satuan = Satuan::select('id', 'nama')->where('id', $produk->satuan_id)->get();
    
        $satuan_konversi = SatuanKonversi::select('id', 'nama_konversi as nama')
            ->where("produk_id", $request->produk_id)
            ->where("status", 1)
            ->get();
    
        // Create a new collection and add a row with id = 0 from Satuan
        $data = collect([
            ['id' => 0, 'nama' => $satuan->first()->nama]
        ])->merge($satuan_konversi);
    
        return DataTables::of($data)
            ->make(true);
    }
    

    public function getDataSupplier(Request $request){
        $data = Supplier::query();


        return DataTables::of($data)
            ->make(true);
    }

    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori');
        $data->where("status", 1)->where("pembelian", 1)->get();


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
