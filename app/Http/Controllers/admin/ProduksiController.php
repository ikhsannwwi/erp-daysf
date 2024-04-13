<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Formula;
use App\Models\Produksi;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\FormulaDetail;
use App\Models\TransaksiStok;
use App\Models\ProduksiDetail;
use App\Models\SatuanKonversi;
use App\Http\Controllers\Controller;

class ProduksiController extends Controller
{
    private static $module = "produksi";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.produksi.index');
    }
    
    public function getData(Request $request){
        $data = Produksi::query()->with('produk');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.produksi.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailProduksi">
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

        return view('administrator.produksi.add');
    }

    
    function convertToRoman($number)
    {
        $romans = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romans[$number - 1];
    }

    function generateNomorProduksi(){
        $today = Carbon::now();
        $formattedDate = $today->format('Y') . '/'. $this->convertToRoman($today->format('m')) . '/' . $today->format('d');

        // Cari nomor urut transaksi terakhir pada hari ini
        $lastTransaction = Produksi::whereDate('tanggal', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_produksi, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'PR' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

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
            'gudang' => 'required',
            'produk' => 'required',
            'formula' => 'required',
            'jumlah_produksi' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $log = [];

            $data = Produksi::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'no_produksi' => $this->generateNomorProduksi(),
                'gudang_id' => $request->gudang,
                'produk_id' => $request->produk,
                'formula_id' => $request->formula,
                'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah_produksi),
                'transaksi_stok_id' => 0,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);

            $stok = TransaksiStok::create([
                'tanggal' => date('Y-m-d', strtotime($data->tanggal)),
                'gudang_id' => $data->gudang_id,
                'produk_id' => $data->produk_id,
                'metode_transaksi' => 'masuk',
                'jenis_transaksi' => static::$module,
                'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah_produksi),
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            $data->update(['transaksi_stok_id' => $stok->id]);
            
            $log[] = [
                'Produksi' => $data->toArray(),
                'Transaksi Stok' => $stok->toArray()
            ];
            foreach ($request->detail as $row) {
                $produksi_detail = ProduksiDetail::create([
                    'produksi_id' => $data->id,
                    'formula_detail_id' => $row['id'],
                    'produk_id' => $row['produk'],
                    'transaksi_stok_id' => 0,
                    'jumlah_unit' => str_replace(['.', ','], '', $row['jumlah_unit']),
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);

                if ($row['satuan'] === 0 || $row['satuan'] === "0") {
                    $kuantitas = str_replace(['.', ','], '', $row['jumlah_unit']);
                } else {
                    $satuan_konversi = SatuanKonversi::find($row['satuan']);
                    $kuantitas = $satuan_konversi->kuantitas_satuan * str_replace(['.', ','], '', $row['jumlah_unit']);
                }
                
                $stok_detail = TransaksiStok::create([
                    'tanggal' => date('Y-m-d', strtotime($data->tanggal)),
                    'gudang_id' => $data->gudang_id,
                    'produk_id' => $row['produk'],
                    'metode_transaksi' => 'keluar',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $kuantitas,
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $produksi_detail->update(['transaksi_stok_id' => $stok_detail->id]);
                $log['Detail'][] = [
                    'Data Detail' => $produksi_detail->toArray(),
                    'Transaksi Stok' => $stok_detail->toArray()
                ];
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $log]);
            DB::commit();
            return redirect()->route('admin.produksi')->with('success', 'Data berhasil disimpan.');
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

        $data = Produksi::with([
            'detail' => function($query){
                $query->with([
                    'formula_detail' => function($query_formula_detail){
                        $query_formula_detail->with('produk.satuan', 'satuan_konversi');
                    }
                ]);
            }
        ])->with('produk')->with('gudang')->with('formula')->find($id);

        return view('administrator.produksi.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Produksi::find($id);
        $data_stok = TransaksiStok::find($data->transaksi_stok_id);
        
        $rules = [
            'tanggal' => 'required',
            'gudang' => 'required',
            'produk' => 'required',
            'formula' => 'required',
            'jumlah_produksi' => 'required',
            'detail' => 'required',
        ];
        
        $request->validate($rules);
        // dd($request);

        $previousData = [
            'produksi' => $data->toArray(),
            'detail' => []
        ];

        $updates = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'formula_id' => $request->formula,
            'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah_produksi),
            'transaksi_stok_id' => 0,
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        $update_stok = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'metode_transaksi' => 'masuk',
            'jenis_transaksi' => static::$module,
            'jumlah_unit' => str_replace(['.', ','], '', $request->jumlah_produksi),
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];
        
        try {
            DB::beginTransaction();
            $data->update($updates);
            $data_stok->update($update_stok);
            $data->update(['transaksi_stok_id' => $data_stok->id]);
            
            $updatedData = [
                'produksi' => array_intersect_key($updates, $data->getOriginal()),
                'detail' => []
            ];
            
            foreach ($request->detail as $key => $row) {
                $commonFields = [
                    'produk_id' => $row['produk'],
                ];
                
                $detail_updates = array_merge($commonFields, [
                    'produksi_id' => $data->id,
                    'formula_detail_id' => $row['formula_detail_id'],
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
    
                $update_stok_detail = array_merge($commonFields, [
                    'tanggal' => date('Y-m-d', strtotime($data->tanggal)),
                    'gudang_id' => $data->gudang_id,
                    'metode_transaksi' => 'keluar',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => $kuantitas,
                    'created_by' => !empty($row['id']) ? auth()->user()->kode : '',
                    'updated_by' => empty($row['id']) ? auth()->user()->kode : '',
                ]);

                if ($key === 0) {
                    // Check if the 'id' is empty
                    if (empty($row['id'])) {
                        // Delete existing details and stok for the current produksi_id
                        $detail_before = ProduksiDetail::where('produksi_id', $data->id)->get();
                        foreach ($detail_before as $detail_row) {
                            // Check if transaksi_stok_id is not empty before deleting
                            if (!empty($detail_row->transaksi_stok_id)) {
                                $stok_detail = TransaksiStok::find($detail_row->transaksi_stok_id);
                                if ($stok_detail) {
                                    $stok_detail->delete();
                                }
                            }
                            $detail_row->delete();
                        }
                    }
                }
    
                if (!empty($row['id'])) {
                    $detail = ProduksiDetail::find($row['id']);
                    $transaksi_stok = TransaksiStok::find($row['transaksi_stok_id']);
                    
                    $previousData['detail']['produksi'][] = $detail->toArray();
                    $previousData['detail']['transaksi_stok'][] = $transaksi_stok->toArray();
                    
                    $detail->update($detail_updates);
                    $transaksi_stok->update($update_stok_detail);

                    $updatedData['detail']['produksi'][] = array_intersect_key($detail_updates, $detail->getOriginal());
                    $updatedData['detail']['transaksi_stok'][] = array_intersect_key($update_stok_detail, $transaksi_stok->getOriginal());
                } else {
                    $detail = ProduksiDetail::create($detail_updates);
                    $transaksi_stok = TransaksiStok::create($update_stok_detail);
                    $detail->update(['transaksi_stok_id' => $transaksi_stok->id]);

                    $previousData['detail']['produksi'][] = $detail->toArray();
                    $previousData['detail']['transaksi_stok'][] = $transaksi_stok->toArray();
                }
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.produksi')->with('success', 'Data berhasil diupdate.');
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

        $data = Produksi::findOrFail($id);
        $detail = ProduksiDetail::where('produksi_id', $id)->get();

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = [
            $data->toArray(),
            'Transaksi Stok' => [],
            'detail' => []
        ];

        try {
            DB::beginTransaction();
            foreach ($detail as $row) {
                $row->delete();
                $deletedData['detail'][] = $row->toArray();
                $stok_detail = TransaksiStok::find($row->transaksi_stok_id);
                if ($stok_detail) {
                    $deletedData['detail'][] = $stok_detail->toArray();
                    $stok_detail->delete();
                }
            }
            $stok = TransaksiStok::find($data->transaksi_stok_id);
            if ($stok) {
                $deletedData['Transaksi Stok'][] = $stok_detail->toArray();
                $stok->delete();
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
        $data = ProduksiDetail::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $deletedData = [];
        $deletedData[] = $data->toArray();
        
        // Delete the transaction
        try {
            DB::beginTransaction();
            $stok = TransaksiStok::find($data->transaksi_stok_id);
            if ($stok) {
                $deletedData[] = $stok->toArray();
                $stok->delete();
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

    public function updateTotal(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Produksi::find($id);
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

        $data = Produksi::with([
            'detail' => function($query){
                $query->with([
                    'formula_detail' => function($query_formula_detail){
                        $query_formula_detail->with('produk.satuan', 'satuan_konversi');
                    }
                ]);
            }
        ])->with('produk')->with('gudang')->with('formula')->find($id);
        

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
    
    public function getDataFormula(Request $request){
        $data = Formula::query();
        $data->where('produk_id', $request->produk_id)->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function getFormulaDetail(Request $request){
        $data = FormulaDetail::where('formula_id', $request->id)->with([
            'produk' => function($query){
                $query->with('satuan');
            }
        ])->with('satuan_konversi')->get();

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat data.',
        ]);
    }
    
    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori');
        $data->where("status", 1)->where("produksi", 1)->get();


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
