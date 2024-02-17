<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Karyawan;
use App\Models\admin\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Models\admin\UserMember;
use App\Models\admin\OperatorKasir;
use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
    private static $module = "karyawan";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.karyawan.index');
    }
    
    public function getData(Request $request){
        $data = Karyawan::query()->with('departemen');

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
                    $btn .= '<a href="'.route('admin.karyawan.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailKaryawan">
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

        return view('administrator.karyawan.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        
        $rules = [
            'departemen' => 'required',
            'nama' => 'required',
            'nama_depan' => 'required',
            'email' => 'required',
            'telepon' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'tanggal_bergabung' => 'required',
            'jabatan' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);

        function generateKode($length = 8) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        $Kode = generateKode(8);

        $data = Karyawan::create([
            'kode' => $Kode,
            'departemen_id' => $request->departemen,
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'tanggal_lahir' => date('Y-m-d', strtotime($request->tanggal_lahir)),
            'alamat' => $request->alamat,
            'tanggal_bergabung' => date('Y-m-d', strtotime($request->tanggal_bergabung)),
            'jabatan' => $request->jabatan,
            'keteragan' => $request->keteragan,
            'status' => $request->status,
            'created_by' => auth()->user() ? auth()->user()->kode : '',
        ]);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
        return redirect()->route('admin.karyawan')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Karyawan::with('departemen')->find($id);

        return view('administrator.karyawan.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Karyawan::find($id);

        $rules = [
            'departemen' => 'required',
            'nama' => 'required',
            'nama_depan' => 'required',
            'email' => 'required',
            'telepon' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'tanggal_bergabung' => 'required',
            'jabatan' => 'required',
            'status' => 'required',
        ];

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'departemen_id' => $request->departemen,
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'tanggal_lahir' => date('Y-m-d', strtotime($request->tanggal_lahir)),
            'alamat' => $request->alamat,
            'tanggal_bergabung' => date('Y-m-d', strtotime($request->tanggal_bergabung)),
            'jabatan' => $request->jabatan,
            'keteragan' => $request->keteragan,
            'status' => $request->status,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.karyawan')->with('success', 'Data berhasil diupdate.');
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
        $data = Karyawan::findorfail($id);

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
        $updates = Karyawan::where(["id" => $id])->first();
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

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Karyawan::with('departemen')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function checkEmail(Request $request){
        if($request->ajax()){
            $email = $request->email;
            $id = $request->id;
    
            $userWithEmail = User::where('email', $email);
            $userMemberWithEmail = UserMember::where('email', $email);
            $operatorKasirWithEmail = OperatorKasir::where('email', $email);
            $KaryawanWithEmail = Karyawan::where('email', $email);
    
            if(isset($id)){
                $userWithEmail->where('id', '!=', $id);
                $userMemberWithEmail->where('id', '!=', $id);
                $operatorKasirWithEmail->where('id', '!=', $id);
                $KaryawanWithEmail->where('id', '!=', $id);
            }
    
            $userExists = $userWithEmail->exists() || $userMemberWithEmail->exists() || $operatorKasirWithEmail->exists() || $KaryawanWithEmail->exists();
    
            if($userExists){
                return response()->json([
                    'message' => 'Email sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
    
    public function getDataDepartemen(Request $request){
        $data = Departemen::query();


        return DataTables::of($data)
            ->make(true);
    }
    
    public function checkTelepon(Request $request){
        if($request->ajax()){
            $data = Karyawan::where('telepon', $request->telepon);
            
            if(isset($request->id)){
                $data->where('id', '!=', $request->id);
            }
            // $data->get();

            // dd($request->telepon);
    
            if($data->exists()){
                return response()->json([
                    'message' => 'Telepon sudah dipakai',
                    'valid' => false,
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
}
