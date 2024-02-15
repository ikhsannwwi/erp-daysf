<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\admin\Produk;
use Illuminate\Http\Request;
use App\Models\SatuanKonversi;
use App\Http\Controllers\Controller;

class SatuanKonversiController extends Controller
{
    private static $module = "satuan_konversi";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.satuan_konversi.index');
    }
    
    public function getData(Request $request){
        $data = SatuanKonversi::query()->with('produk');

        if ($request->status ) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
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
                    $btn .= '<a href="'.route('admin.satuan_konversi.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailSatuanKonversi">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }
    
    public function add(){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        return view('administrator.satuan_konversi.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        
        $rules = [
            'produk' => 'required',
            'nama_konversi' => 'required',
            'kuantitas_konversi' => 'required',
            'satuan_id' => 'required',
            'kuantitas_satuan' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);

        $data = SatuanKonversi::create([
            'produk_id' => $request->produk,
            'satuan_id' => $request->satuan_id,
            'kuantitas_konversi' => str_replace([','], '', $request->kuantitas_konversi),
            'kuantitas_satuan' => str_replace([','], '', $request->kuantitas_satuan),
            'nama_konversi' => $request->nama_konversi,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'created_by' => auth()->user() ? auth()->user()->kode : '',
        ]);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
        return redirect()->route('admin.satuan_konversi')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = SatuanKonversi::with('produk')->find($id);

        return view('administrator.satuan_konversi.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = SatuanKonversi::find($id);

        $rules = [
            'produk' => 'required',
            'nama_konversi' => 'required',
            'kuantitas_konversi' => 'required',
            'satuan_id' => 'required',
            'kuantitas_satuan' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'produk_id' => $request->produk,
            'satuan_id' => $request->satuan_id,
            'kuantitas_konversi' => str_replace([','], '', $request->kuantitas_konversi),
            'kuantitas_satuan' => str_replace([','], '', $request->kuantitas_satuan),
            'nama_konversi' => $request->nama_konversi,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.satuan_konversi')->with('success', 'Data berhasil diupdate.');
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
        $data = SatuanKonversi::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $data->toArray();

        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }

    public function changeStatus(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "status")) {
            abort(403);
        }
        
        $data['status'] = $request->status == "Aktif" ? 1 : 0;
        $log = $request->status;
        $id = $request->ix;
        $updates = SatuanKonversi::where(["id" => $id])->first();
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

    public function getDataProduk(Request $request){
        $data = Produk::query()->with('kategori')->with('satuan');

        return DataTables::of($data)
            ->make(true);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = SatuanKonversi::with([
            'produk' => function($query){
                $query->with('satuan');
            }
        ])->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }
}
