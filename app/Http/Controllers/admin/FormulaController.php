<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\Formula;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\FormulaDetail;
use App\Models\SatuanKonversi;
use App\Http\Controllers\Controller;

class FormulaController extends Controller
{
    private static $module = "formula";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.formula.index');
    }
    
    public function getData(Request $request){
        $data = Formula::query()->with('produk');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.formula.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailFormula">
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

        return view('administrator.formula.add');
    }

    
    function convertToRoman($number)
    {
        $romans = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romans[$number - 1];
    }

    function generateNomorFormola(){
        $today = Carbon::now();
        $formattedDate = $today->format('Y') . '/'. $this->convertToRoman($today->format('m')) . '/' . $today->format('d');

        // Cari nomor urut transaksi terakhir pada hari ini
        $lastTransaction = Formula::whereDate('tanggal', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_formula, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'FR' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

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
            'nama' => 'required',
            'produk' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $data = Formula::create([
                'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
                'nama' => $request->nama,
                'produk_id' => $request->produk,
                'no_formula' => $this->generateNomorFormola(),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            foreach ($request->detail as $row) {
                // dd($row);
                $detail = FormulaDetail::create([
                    'formula_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'satuan_id' => $row['satuan'],
                    'jumlah_unit' => str_replace(['.', ','], '', $row['jumlah_unit']),
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.formula')->with('success', 'Data berhasil disimpan.');
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

        $data = Formula::with('detail')->find($id);

        return view('administrator.formula.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Formula::find($id);
        
        $rules = [
            'tanggal' => 'required',
            'nama' => 'required',
            'produk' => 'required',
            'detail' => 'required',
        ];
        
        $request->validate($rules);
        // dd($request);

        $previousData = [
            'formula' => $data->toArray(),
            'detail' => []
        ];

        $updates = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'nama' => $request->nama,
            'produk_id' => $request->produk,
            'keterangan' => $request->keterangan,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];
        
        try {
            DB::beginTransaction();
            $data->update($updates);
            
            $updatedData = [
                'formula' => array_intersect_key($updates, $data->getOriginal()),
                'detail' => []
            ];
            // dd($request->detail);
            foreach ($request->detail as $row) {
                $commonFields = [
                    'produk_id' => $row['produk'],
                    'satuan_id' => $row['satuan'],
                ];
                
                $detail_updates = array_merge($commonFields, [
                    'formula_id' => $data->id,
                    'jumlah_unit' => str_replace(['.', ','], '', $row['jumlah_unit']),
                    'created_by' => !empty($row['id']) ? auth()->user()->kode : '',
                    'updated_by' => empty($row['id']) ? auth()->user()->kode : '',
                ]);
    
                if (!empty($row['id'])) {
                    $detail = FormulaDetail::find($row['id']);
                    
                    $previousData['detail']['formula'] = $detail->toArray();
                    
                    $detail->update($detail_updates);

                    $previousData['detail']['formula'] = array_intersect_key($detail_updates, $detail->getOriginal());
                } else {
                    $detail = FormulaDetail::create($detail_updates);
                    $previousData['detail']['formula'] = $detail->toArray();
                }
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.formula')->with('success', 'Data berhasil diupdate.');
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

        $data = Formula::findOrFail($id);
        $detail = FormulaDetail::where('formula_id', $id)->get();

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
        $data = FormulaDetail::findorfail($id);

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

        $data = Formula::with([
            'detail' => function ($query) {
                $query->with(['satuan_konversi',
                    'produk' => function($query_produk){
                        $query_produk->with('satuan');
                    }
                ]);
            },
            'produk'
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

    public function getDataProdukProduksi(Request $request){
        $data = Produk::query()->with('kategori');
        $data->where("status", 1)->where("produksi", 1)->get();

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
    
    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori');
        $data->where("status", 1)->where("pembelian", 1)->get();

        return DataTables::of($data)
            ->make(true);
    }
}
