<?php

namespace App\Http\Controllers\administrator;

use DataTables;
use App\Models\admin\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\admin\UserGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return view('administrator.users.index');
    }
    
    public function getData(Request $request){
        $data = User::query()->with('user_group');

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
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                // if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                // endif;
                // if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.users.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                // endif;
                // if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailUser">
                    Detail
                </a>';
                // endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function add(){
        return view('administrator.users.add');
    }
    
    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
            'user_group' => 'required',
            'status' => 'required',
        ]);
    
        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_group_id' => $request->user_group,
            'status' => $request->status,
            'remember_token' => Str::random(60),
        ]);
    
        return redirect()->route('admin.users')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        $data = User::find($id);

        return view('administrator.users.edit',compact('data'));
    }
    
    public function update(Request $request){
        $id = $request->id;
        $data = User::find($id);
    
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'user_group' => 'required',
        ];
    
        if ($request->password) {
            $rules['password'] = 'required|min:8';
            $rules['konfirmasi_password'] = 'required|min:8|same:password';
        }
    
        $request->validate($rules);
    
        $updates = [
            'name' => $request->name,
            'email' => $request->email,
            'user_group_id' => $request->user_group,
            'status' => $request->status,
            'remember_token' => Str::random(60),
        ];
    
        if ($request->password) {
            $updates['password'] = Hash::make($request->password);
        }
    
        $data->update($updates);
    
        return redirect()->route('admin.users')->with('success', 'Data berhasil diupdate.');
    }
    
    
    
    public function delete(Request $request){
        // Ensure you have authorization mechanisms here before proceeding to delete data.
    
        $id = $request->id;
    
        // Find the user based on the provided ID.
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }
    
        // Delete the user.
        $user->delete();
    
        // Write logs if needed.
        // createLog(static::$module, __FUNCTION__, $id);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna telah dihapus.',
        ]);
    }
    
    
    public function getDetail($id){
        $data = User::with('user_group')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail user.',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data['status'] = $request->status == "Aktif" ? 1 : 0;
        $id = $request->ix;
        User::where(["id" => $id])->update($data);

        //Write log
        // createLog(static::$module, __FUNCTION__, $id);
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
    
    public function checkEmail(Request $request){
        if($request->ajax()){
            $users = User::where('email', $request->email);
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
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
}
