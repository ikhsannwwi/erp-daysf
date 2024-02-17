<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\Karyawan;
use App\Models\admin\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\admin\Profile;
use App\Models\admin\UserGroup;
use App\Models\admin\UserMember;
use App\Models\admin\OperatorKasir;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class OperatorKasirController extends Controller
{
    private static $module = "operator_kasir";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.operator_kasir.index');
    }
    
    public function getData(Request $request){
        $data = OperatorKasir::query()->with('user_group');

        if ($request->status || $request->usergroup) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->usergroup != "") {
                $usergroupid = $request->usergroup ;
                $data->where("user_group_id", $usergroupid);
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
                    $btn .= '<a href="'.route('admin.operator_kasir.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailUser">
                    Detail
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

        return view('administrator.operator_kasir.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
            'user_group' => 'required',
            'status' => 'required',
        ]);
    
        $data = OperatorKasir::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_group_id' => $request->user_group,
            'status' => $request->status,
            'kode' => $request->kode,
            'remember_token' => Str::random(60),
        ]);

        $profile = Profile::create([
            'user_kode' => $data['kode'],
            'sosial_media' => '{
                "linkedin": "",
                "twitter": "",
                "instagram": "",
                "facebook": ""
              }',
        ]);
    
        createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
        return redirect()->route('admin.operator_kasir')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = OperatorKasir::find($id);

        return view('administrator.operator_kasir.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = OperatorKasir::find($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'user_group' => 'required',
            'kode' => 'required|unique:users,kode,'.$id,
        ];

        if ($request->password) {
            $rules['password'] = 'required|min:8';
            $rules['konfirmasi_password'] = 'required|min:8|same:password';
        }

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'name' => $request->name,
            'email' => $request->email,
            'user_group_id' => $request->user_group,
            'status' => $request->status,
            'kode' => $request->kode,
            'remember_token' => Str::random(60),
        ];

        if ($request->password) {
            $updates['password'] = Hash::make($request->password);
        }

        // Check if a profile exists for the user
        $profile = Profile::where('user_kode', $data->kode)->firstOrNew([
            'user_kode' => $data->kode,
            'sosial_media' => '{"linkedin":"","twitter":"","instagram":"","facebook":""}',
        ]);

        // Update the profile data
        $profile->user_kode = $updates['kode'];
        $profile->save();

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.operator_kasir')->with('success', 'Data berhasil diupdate.');
    }

    
    
    
    public function delete(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }

        // Ensure you have authorization mechanisms here before proceeding to delete data.

        $id = $request->id;

        // Find the user based on the provided ID.
        $user = OperatorKasir::findorfail($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $user->toArray();

        // Delete the user.
        $user->delete();

        $profile = Profile::where('user_kode', $user->kode)->first();

        if ($profile) {
            // Check if the profile is being force-deleted
            $profile->delete();
        }

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => ['User' => $deletedData, 'User Profile' => $profile]]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna telah dihapus.',
        ]);
    }

    
    
    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = OperatorKasir::with('user_group')->with('profile')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail user.',
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
        $updates = OperatorKasir::where(["id" => $id])->first();
        // Simpan data sebelum diupdate
        $previousData = $updates->toArray();
        $updates->update($data);

        //Write log
        createLog(static::$module, __FUNCTION__, $id, ['Data User' => $previousData,'Statusnya diubah menjadi' => $log]);
        return response()->json([
            'status' => 'success',
            'message' => 'Status telah diubah.',
        ]);
    }
    
    public function getUserGroup(){
        $usergroup = UserGroup::all();

        return response()->json([
            'usergroup' => $usergroup,
        ]);
    }
    
    public function generateKode(){
        $generateKode = 'daysf-kasir-' . substr(uniqid(), -5);

        return response()->json([
            'generateKode' => $generateKode,
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
    
    public function checkKode(Request $request){
        if($request->ajax()){
            $users = OperatorKasir::where('kode', $request->kode)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Kode sudah dipakai',
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

        return view('administrator.operator_kasir.arsip');
    }

    public function getDataArsip(Request $request){
        $data = OperatorKasir::query()->with('user_group')->onlyTrashed();

        if ($request->status || $request->usergroup) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->usergroup != "") {
                $usergroupid = $request->usergroup ;
                $data->where("user_group_id", $usergroupid);
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
                if (isAllowed(static::$module, "restore")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-primary restore btn-sm me-3 ">
                    Restore
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function restore(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "restore")) {
            abort(403);
        }
        
        $id = $request->id;
        $data = OperatorKasir::withTrashed()->find($id);
        $profile = Profile::withTrashed()->where('user_kode', $data->kode)->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        if (!$profile) {
            $profile = Profile::create([
                'user_kode' => $data->kode,
            ]);
            $userProfiletoarray = '';
        } else {
            # code...
            $userProfiletoarray = "'User Profile' => $profile->toArray()";
        }
        // Simpan data sebelum diupdate
        $previousData = [
            'User' => $data->toArray(),
            $userProfiletoarray
        ];

        $data->restore();
        if (!empty($profile)) {
            $profile->restore();
        }

        $updated = ['User' => $data, 'User Profile' => $profile];

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dipulihkan' => $updated]);

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

        $data = OperatorKasir::withTrashed()->find($id);
        $profile = Profile::withTrashed()->where('user_kode',$data->kode)->first();

        if (!$data) {
            return redirect()->route('admin.operator_kasir.arsip')->with('error', 'Data tidak ditemukan.');
        }

        $data->forceDelete();
        if (!empty($profile)) {
            $profile->forceDelete();
            $dataJsonProfile = $profile;
        } else {
            $dataJsonProfile = '';
        }

        $dataJson = [
            $data,$dataJsonProfile
        ];

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, $dataJson);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus secara permanent.',
        ]);
    }
}
