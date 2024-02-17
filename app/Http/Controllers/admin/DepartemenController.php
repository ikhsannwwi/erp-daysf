<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartemenController extends Controller
{
    private static $module = "departemen";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.departemen.index');
    }
    
    public function getData(Request $request){
        $data = Departemen::query()->with('karyawan');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.departemen.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
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

        return view('administrator.departemen.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'required',
        ]);
    
        $data = Departemen::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'kepala_departemen' => $request->kepala_departemen ? $request->kepala_departemen : 0,
        ]);
    
        createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
        return redirect()->route('admin.departemen')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Departemen::with('karyawan')->find($id);

        return view('administrator.departemen.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Departemen::find($id);

        $rules = [
            'nama' => 'required',
            'deskripsi' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'kepala_departemen' => $request->kepala_departemen ? $request->kepala_departemen : 0,
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.departemen')->with('success', 'Data berhasil diupdate.');
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
        $data = Departemen::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $data->toArray();

        // Delete the data.
        $data->delete();


        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ]);
    }
    
    public function getDataKaryawan(Request $request){
        $data = Karyawan::query();
        $data->where("status", 1)->get();


        return DataTables::of($data)
            ->make(true);
    }

    public function checkNama(Request $request){
        if($request->ajax()){
            $users = Departemen::where('nama', $request->nama);
            
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
}
