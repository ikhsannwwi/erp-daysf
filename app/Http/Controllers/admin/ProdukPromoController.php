<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Toko;
use App\Models\ProdukPromo;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\TransaksiStok;
use App\Models\ProdukPromoDetail;
use App\Http\Controllers\Controller;

class ProdukPromoController extends Controller
{
    private static $module = "produk_promo";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.produk_promo.index');
    }
    
    public function getData(Request $request){
        $data = ProdukPromo::query();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete mx-1">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.produk_promo.edit',$row->id).'" class="btn btn-primary btn-sm mx-1">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#detailProdukPromo">
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

        return view('administrator.produk_promo.add');
    }

    
    function convertToRoman($number)
    {
        $romans = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romans[$number - 1];
    }

    function generateNomorPromo(){
        $today = Carbon::now();
        $formattedDate = $today->format('Y') . '/'. $this->convertToRoman($today->format('m')) . '/' . $today->format('d');

        // Cari nomor urut transaksi terakhir pada hari ini
        $lastTransaction = ProdukPromo::whereDate('created_at', $today)
            ->latest('created_at') // Mencari yang terakhir
            ->first();

        // Nomor urut transaksi
        $nomorUrut = $lastTransaction ? (int)substr($lastTransaction->no_promo, -4) + 1 : 1;

        // Format nomor transaksi
        $nomorTransaksi = 'PPR' . '/' . $formattedDate . '/' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        return $nomorTransaksi;
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        // dd($request);
        $rules = [
            'periode' => 'required',
            'nama' => 'required',
            'jenis' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $tanggal_string = $request->periode;

            // Pisahkan tanggal mulai dan berakhir menggunakan pemisah "~"
            $tanggal_split = explode("~", $tanggal_string);

            // Hilangkan spasi ekstra dan trim tanggal mulai dan berakhir
            $tanggal_mulai = trim($tanggal_split[0]);
            $tanggal_berakhir = trim($tanggal_split[1]);

            // Konversi ke format tanggal yang diinginkan
            $tanggal_mulai = date('Y-m-d H:i:s', strtotime($tanggal_mulai));
            $tanggal_berakhir = date('Y-m-d H:i:s', strtotime($tanggal_berakhir));

            $data = ProdukPromo::create([
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_berakhir' => $tanggal_berakhir,
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'no_promo' => $this->generateNomorPromo(),
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
            
            $log_detail = [];
            foreach ($request->detail as $row) {
                $detail = ProdukPromoDetail::create([
                    'produk_promo_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'diskon' => ($request->jenis === 'persentase' ? str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_persentase_harga']) : str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_harga_tetap'])),
                    'persentase' => ($request->jenis === 'persentase' ? str_replace(['.', ',', '%', ' '], '', $row['diskon_persentase']) : null),
                    'total_stok_promo' => str_replace(['.', ','], '', $row['total_stok_promo']),
                    'batas_pembelian' => str_replace(['.', ','], '', $row['batas_pembelian']),
                    'created_by' => auth()->user() ? auth()->user()->kode : '',
                ]);
                $log_detail[] = $detail;
            }
            
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => [$data, 'Detail' => $log_detail]]);
            DB::commit();
            return redirect()->route('admin.produk_promo')->with('success', 'Data berhasil disimpan.');
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

        $data = ProdukPromo::with([
            'detail' => function($query){
                $query->with('produk');
            }
        ])->find($id);

        return view('administrator.produk_promo.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = ProdukPromo::find($id);

        $rules = [
            'periode' => 'required',
            'nama' => 'required',
            'jenis' => 'required',
            'detail' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = [
            'Master' => $data->toArray(),
            'Detail' => []
        ];

        try {
            DB::beginTransaction();

            $tanggal_string = $request->periode;

            // Pisahkan tanggal mulai dan berakhir menggunakan pemisah "~"
            $tanggal_split = explode("~", $tanggal_string);

            // Hilangkan spasi ekstra dan trim tanggal mulai dan berakhir
            $tanggal_mulai = trim($tanggal_split[0]);
            $tanggal_berakhir = trim($tanggal_split[1]);

            // Konversi ke format tanggal yang diinginkan
            $tanggal_mulai = date('Y-m-d H:i:s', strtotime($tanggal_mulai));
            $tanggal_berakhir = date('Y-m-d H:i:s', strtotime($tanggal_berakhir));

            $updates = [
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_berakhir' => $tanggal_berakhir,
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user() ? auth()->user()->kode : '',
            ];

            $updatedData = [
                'Master' => array_intersect_key($updates, $data->getOriginal()),
                'Detail' => []
            ];
            
            foreach ($request->detail as $row) {
                $update_details = [
                    'produk_promo_id' => $data->id,
                    'produk_id' => $row['produk'],
                    'diskon' => ($request->jenis === 'persentase' ? str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_persentase_harga']) : str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_harga_tetap'])),
                    'persentase' => ($request->jenis === 'persentase' ? str_replace(['.', ',', '%', ' '], '', $row['diskon_persentase']) : null),
                    'total_stok_promo' => str_replace(['.', ','], '', $row['total_stok_promo']),
                    'batas_pembelian' => str_replace(['.', ','], '', $row['batas_pembelian']),
                ];

                if (!empty($row['id'])) {
                    $update_details['updated_by'] = auth()->user() ? auth()->user()->kode : '';
                    $detail = ProdukPromoDetail::find($row['id']);
                    $previousData['Detail'][] = $detail;
                    $detail->update($update_details);
                    $updatedData['Detail'][] =  array_intersect_key($update_details, $detail->getOriginal());
                }else {
                    $detail = ProdukPromoDetail::create([
                        'produk_promo_id' => $data->id,
                        'produk_id' => $row['produk'],
                        'diskon' => ($request->jenis === 'persentase' ? str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_persentase_harga']) : str_replace(['.', ',', 'Rp', ' '], '', $row['diskon_harga_tetap'])),
                        'persentase' => ($request->jenis === 'persentase' ? str_replace(['.', ',', '%', ' '], '', $row['diskon_persentase']) : null),
                        'total_stok_promo' => str_replace(['.', ','], '', $row['total_stok_promo']),
                        'batas_pembelian' => str_replace(['.', ','], '', $row['batas_pembelian']),
                        'created_by' => auth()->user() ? auth()->user()->kode : '',
                    ]);
                    $updatedData['Detail'][] = $detail->toArray();
                }
            }
            
            // Filter only the updated data
    
            $data->update($updates);
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
            DB::commit();
            return redirect()->route('admin.produk_promo')->with('success', 'Data berhasil diupdate.');
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

        $data = ProdukPromo::findOrFail($id);
        $detail = ProdukPromoDetail::where('produk_promo_id', $id)->get();

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $log = [];
        $log[] = $data->toArray();

        try {
            DB::beginTransaction();
            foreach ($detail as $row) {
                $log['Detail'][] = $row->toArray();
                $row->delete();
            }
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $log]);
            
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
        $data = ProdukPromoDetail::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $log = $data->toArray();

        // Delete the transaction
        try {
            DB::beginTransaction();
            $data->delete();
    
            createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $log]);
            
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

        $data = ProdukPromo::with([
            'detail' => function($query){
                $query->with('produk');
            }
        ])->find($id);
        

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori')->with('satuan');
        $data->where("status", 1)->where("penjualan", 1)->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function getDataStok(Request $request)
    {
        $jumlah = 0;

        // Ambil jumlah stok masuk
        $stok_masuk = TransaksiStok::where('produk_id', $request->produk)
            // ->where('toko_id', $request->toko)
            ->whereIn('metode_transaksi', ['masuk']);

        if (!empty($request->created_at)) {
            $stok_masuk->where('created_at', '<', $request->created_at);
        }

        $stok_masuk = $stok_masuk->sum('jumlah_unit');

        // Ambil jumlah stok keluar
        $stok_keluar = TransaksiStok::where('produk_id', $request->produk)
            // ->where('toko_id', $request->toko)
            ->whereIn('metode_transaksi', ['keluar']);

        if (!empty($request->created_at)) {
            $stok_keluar->where('created_at', '<', $request->created_at);
        }

        $stok_keluar = $stok_keluar->sum('jumlah_unit');

        $jumlah += $stok_masuk - $stok_keluar;

        return response()->json([
            'data' => $jumlah,
            'status' => 'success',
            'message' => 'Sukses memuat data.',
        ]);
    }
}
