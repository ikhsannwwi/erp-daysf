<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\SatuanKonversi;
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
            'metode' => 'required|in:masuk,keluar,migrasi_gudang,migrasi_ke_toko',
            'jumlah' => 'required',
            'satuan' => 'required',
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
        if ($request->metode === 'keluar' || $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko') {
            if ($jumlah < 0) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
        }

        try {
            if ($request->satuan === 0 || $request->satuan === "0") {
                $jumlah_penyesuaian = str_replace(['.', ','], '', $request->jumlah);
            } else {
                $satuan_konversi = SatuanKonversi::find($request->satuan);
                $jumlah_penyesuaian = $satuan_konversi->kuantitas_satuan * str_replace(['.', ','], '', $request->jumlah);
            }

            DB::beginTransaction();
            $data = PenyesuaianStok::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'satuan_id' => $request->satuan,
                'gudang_id' => $request->gudang,
                'produk_id' => $request->produk,
                'migrasi_id' => $request->metode === 'migrasi_gudang' ? $request->migrasi_gudang : $request->migrasi_toko,
                'metode_transaksi' => $request->metode,
                'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                'keterangan' => $request->keterangan,
                'transaksi_stok_id' => 0,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            $transaksi = TransaksiStok::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'gudang_id' => $request->gudang,
                'produk_id' => $request->produk,
                'metode_transaksi' => $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko' ? 'keluar' : $request->metode,
                'jenis_transaksi' => static::$module,
                'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            $data->update(['transaksi_stok_id' => $transaksi->id]);
            
            if ($request->metode === 'migrasi_gudang') {
                $migrasi_transaksi_stok = TransaksiStok::create([
                    'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                    'gudang_id' => $request->migrasi_gudang,
                    'produk_id' => $request->produk,
                    'metode_transaksi' => 'masuk',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $data->update(['migrasi_transaksi_stok_id' => $migrasi_transaksi_stok->id]);
            } else if ($request->metode === 'migrasi_ke_toko') {
                $migrasi_transaksi_stok = TransaksiStok::create([
                    'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                    'gudang_id' => 0,
                    'toko_id' => $request->migrasi_toko,
                    'produk_id' => $request->produk,  
                    'metode_transaksi' => 'masuk',
                    'jenis_transaksi' => static::$module,
                    'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $data->update(['migrasi_transaksi_stok_id' => $migrasi_transaksi_stok->id]);
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => [$data, 'Transaksi Stok' => $transaksi]]);
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

        $data = PenyesuaianStok::with(['migrasiToko','migrasiGudang'])->find($id);

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
        $migrasi_transaksi_stok = TransaksiStok::find($data->migrasi_transaksi_stok_id);

        $rules = [
            'tanggal' => 'required',
            'gudang' => 'required',
            'produk' => 'required',
            'metode' => 'required|in:masuk,keluar,migrasi_gudang,migrasi_ke_toko',
            'jumlah' => 'required',
            'satuan' => 'required',
        ];

        $request->validate($rules);

        $previousData = [
            'penyesuaian_stok' => $data->toArray(),
            'transaksi_stok' => $transaksi_stok->toArray()
        ];

        if ($request->satuan === 0 || $request->satuan === "0") {
            $jumlah_penyesuaian = str_replace(['.', ','], '', $request->jumlah);
        } else {
            $satuan_konversi = SatuanKonversi::find($request->satuan);
            $jumlah_penyesuaian = $satuan_konversi->kuantitas_satuan * str_replace(['.', ','], '', $request->jumlah);
        }

        $updates = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'satuan_id' => $request->satuan,
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'metode_transaksi' => $request->metode,
            'migrasi_id' => $request->metode === 'migrasi_gudang' ? $request->migrasi_gudang : $request->migrasi_toko,
            'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        $update_stok = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'gudang_id' => $request->gudang,
            'produk_id' => $request->produk,
            'metode_transaksi' => $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko' ? 'keluar' : $request->metode,
            'jenis_transaksi' => static::$module,
            'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
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

            if ($request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko') {
                $is_gudang = $request->metode === 'migrasi_gudang';
                $gudang_id = $is_gudang ? $request->migrasi_gudang : 0;
                if (empty($migrasi_transaksi_stok)) {
                    $migrasi_transaksi_stok = TransaksiStok::create([
                        'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                        'gudang_id' => $is_gudang ? $gudang_id : 0,
                        'toko_id' => $is_gudang ? 0 : $request->migrasi_toko,
                        'produk_id' => $request->produk,
                        'metode_transaksi' => 'masuk',
                        'jenis_transaksi' => static::$module,
                        'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                        'updated_by' => auth()->user() ? auth()->user()->kode : '',
                    ]);
                    $data->update(['migrasi_transaksi_stok_id' => $migrasi_transaksi_stok->id]);
                } else {
                    $update_stok_migrasi = [
                        'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                        'gudang_id' => $is_gudang ? $gudang_id : 0,
                        'toko_id' => $is_gudang ? 0 : $request->migrasi_toko,
                        'produk_id' => $request->produk,
                        'metode_transaksi' => 'masuk',
                        'jenis_transaksi' => static::$module,
                        'jumlah_unit' => str_replace(['.', ','], '', $jumlah_penyesuaian),
                        'updated_by' => auth()->user() ? auth()->user()->kode : '',
                    ];
                    $migrasi_transaksi_stok->update($update_stok_migrasi);
                }
            } else {
                if (!empty($migrasi_transaksi_stok)) {
                    $data->update(['migrasi_transaksi_stok_id' => 0, 'migrasi_id' => 0]);
                    $migrasi_transaksi_stok->delete();
                }
            }
            
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

        $log = [];
        $log[] = $data->toArray();
        
        $stok = TransaksiStok::find($data->transaksi_stok_id);
        if ($stok) {
            $log['Transaksi Stok'][] = $stok->toArray();
            $stok->delete();
        }

        if (!empty($data->migrasi_transaksi_stok_id)) {
            $migrasi_stok = TransaksiStok::find($data->migrasi_transaksi_stok_id);
            if ($migrasi_stok) {
                $log['Transaksi Stok'][] = $migrasi_stok->toArray();
                $migrasi_stok->delete();
            }
        }

        $data->delete();

        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $log]);

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
    
    // public function checkStock(Request $request){
    //     $jumlah = 0;

    //     $data_stok_masuk = TransaksiStok::where('produk_id', $request->produk)
    //         ->where('gudang_id', $request->gudang)
    //         ->whereIn('metode_transaksi', ['masuk']);
        
    //     if (!empty($request->id)) {
    //         $data = PenyesuaianStok::find($request->id);
    //         $data_stok_masuk->whereNot('id', $data->transaksi_stok_id);
    //     }

    //     $stok_masuk = $data_stok_masuk->sum('jumlah_unit');

    //     // Ambil jumlah stok keluar
    //     $data_stok_keluar = TransaksiStok::where('produk_id', $request->produk)
    //         ->where('gudang_id', $request->gudang)
    //         ->whereIn('metode_transaksi', ['keluar']);

    //     if (!empty($request->id)) {
    //         $data = PenyesuaianStok::find($request->id);
    //         $data_stok_keluar->whereNot('id', $data->transaksi_stok_id);
    //     }

    //     $stok_keluar = $data_stok_keluar->sum('jumlah_unit');

    //     $jumlah += $stok_masuk - $stok_keluar;

    //     if ($request->metode === 'keluar' || $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko') {
    //         if ($jumlah < 0 ) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Stok tidak mencukupi',
    //                 'valid' => false
    //             ]);
    //         }else if($jumlah < intVal(str_replace(['.',','], '', $request->jumlah))){
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Stok tidak mencukupi',
    //                 'valid' => false
    //             ]);
    //         }else {
    //             return response()->json([
    //                 'message' => '1',
    //                 'valid' => true
    //             ]);
    //         }
    //     }else {
    //         return response()->json([
    //             'message' => '2',
    //             'valid' => true
    //         ]);
    //     }
    // }

    public function checkStock(Request $request){
        $jumlah = TransaksiStok::where('produk_id', $request->produk)
            ->where('gudang_id', $request->gudang);
    
        if (!empty($request->id)) {
            $data = PenyesuaianStok::find($request->id);
            $jumlah->whereNotIn('id', [$data->transaksi_stok_id]);
        }
    
        if ($request->metode === 'keluar' || $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko') {
            $jumlah->whereIn('metode_transaksi', ['masuk', 'keluar']);
        } else {
            $jumlah->whereIn('metode_transaksi', ['masuk']);
        }
    
        $jumlah = $jumlah->where('metode_transaksi', 'masuk')->sum('jumlah_unit') - $jumlah->where('metode_transaksi', 'keluar')->sum('jumlah_unit');
    
        if ($request->metode === 'keluar' || $request->metode === 'migrasi_gudang' || $request->metode === 'migrasi_ke_toko') {
            if ($jumlah < 0 || $jumlah < intVal(str_replace(['.',','], '', $request->jumlah))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'message' => '1',
                    'valid' => true
                ]);
            }
        } else {
            return response()->json([
                'message' => '2',
                'valid' => true
            ]);
        }
    }
    
}
