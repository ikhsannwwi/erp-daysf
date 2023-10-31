<?php

namespace App\Http\Controllers\admin;

use DataTables;
use App\Models\admin\Module;
use Illuminate\Http\Request;
use App\Models\admin\UserGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\admin\UserGroupPermission;

class UserGroupController extends Controller
{
    private static $module = "user_group";

    public function index()
    {
        // Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        $modules = Module::with("access")->get();

        return view("administrator.user_groups.index",compact('modules'));
    }

    public function getData(Request $request)
    {
        $data = UserGroup::query();

        if ($request->status != "") {
            $status = $request->status == "Aktif" ? 1 : 0;
            $data->where("status", $status)->get();
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
                    $btn .= '<a href="'.route('admin.user_groups.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailUserGroups">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function add()
    {
        // Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        $modules = Module::with("access")->get();
        return view("administrator.user_groups.add", compact("modules"));
        // return view("administrator.user_groups.examplee.add", compact("modules"));
    }

    public function save(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        $this->validate($request, [
            'name' => 'required'
        ]);

        $data = [
            'name'   => $request->name,
            'status' => $request->has('status') ? 1 : 0,
        ];

        $user_group = UserGroup::create($data);

        $permission = getDefaultPermission();
        $permission_group = $permission[$user_group->id];
        $access = $request->access;

        foreach ($access as $row) {
            if (array_key_exists("module_access", $row)) {
                foreach ($row["module_access"] as $key => $value) {
                    $permission_group[$row['modul_id']][$key] = $value;
                }
            }

            if (array_key_exists($row['modul_id'], $permission_group)) {
                $data_akses = $permission_group[$row['modul_id']];
                foreach ($data_akses as $modul_akses => $status) {
                    $data = [
                        "user_group_id"     => $user_group->id,
                        "module_access_id"  => $modul_akses,
                        "status"            => $status
                    ];

                    $content = $data;
                    unset($data['status']);
                    $is_exist = UserGroupPermission::where($data)->first();
                    if ($is_exist) {
                        UserGroupPermission::where($data)->update($content);
                    } else {
                        UserGroupPermission::create($content);
                    }
                }
            }
        }
        $permission = getPermissionGroup($user_group->id);


        // Write log after all operations are complete
        createLog(static::$module, __FUNCTION__, $user_group->id, ['data' => $data,'hak akses' => $permission]);

        return redirect(route('admin.user_groups'))->with(['success' => 'Data berhasil disimpan.']);
    }

    public function getDetail($id)
    {
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = UserGroup::find($id);
        if (!$data) {
            return abort(404);
        }
        $modules = Module::with("access")->get();
        $permission = getPermissionGroup($id);

        return response()->json([
            'data' => $data,
            'modules' => $modules,
            'permission' => $permission,
        ]);
    }

    public function edit($id)
    {
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $edit = UserGroup::find($id);
        if (!$edit) {
            return abort(404);
        }
        $modules = Module::with("access")->get();
        $permission = getPermissionGroup($id);
        return view("administrator.user_groups.edit", compact("edit", "modules", "permission"));
    }

    public function update(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $this->validate($request, [
            'name' => 'required'
        ]);

        $data = [
            'name'      => $request->name,
            'status'    => $request->has('status') ? 1 : 0,
        ];

        $id = $request->id;
        $user_group = UserGroup::find($id);

        $user_group_updated = UserGroup::where('id', $id)->first();
        $user_group_updated->update($data);

        $permission = getDefaultPermission();
        $permission_group = $permission[$id];
        $access = $request->access;

        foreach ($access as $row) {
            if (array_key_exists("module_access", $row)) {
                foreach ($row["module_access"] as $key => $value) {
                    $permission_group[$row['modul_id']][$key] = $value;
                }
            }

            if (array_key_exists($row['modul_id'], $permission_group)) {
                $data_akses = $permission_group[$row['modul_id']];
                foreach ($data_akses as $modul_akses => $status) {
                    $data = array(
                        "user_group_id"     => $id,
                        "module_access_id"  => $modul_akses,
                        "status"            => $status
                    );

                    $content = $data;
                    unset($data['status']);
                    $is_exist = UserGroupPermission::where($data)->first();
                    if ($is_exist) {
                        UserGroupPermission::where($data)->update($content);
                    } else {
                        UserGroupPermission::create($content);
                    }
                }
            }
        }

        $permissionAfter = getPermissionGroup($user_group->id);
        //Write log
        createLog(static::$module, __FUNCTION__, $id, [
            'Data Sebelum diupdate' => ['data' => $user_group], 
            'Data Setelah diupdate' => ['data'=> $user_group_updated],
            'hak akses' => $permissionAfter
        ]);

        return redirect(route('admin.user_groups'))->with(['success' => 'Data berhasil di update.']);
    }

    public function delete(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }
        
        // Pastikan Anda memiliki mekanisme otorisasi di sini sebelum melanjutkan menghapus data.

        $id = $request->id;
        
        // Temukan grup pengguna berdasarkan ID yang diberikan.
        $user_group = UserGroup::find($id);
        
        if (!$user_group) {
            return response()->json(['message' => 'User group not found'], 404);
        }

        $log = $user_group;
        // Hapus semua entri hak akses (permissions) terkait dengan grup pengguna ini.
        $user_group->permissions()->delete();

        // Hapus grup pengguna.
        $data = $user_group->delete();

        // Tulis log jika diperlukan.
        createLog(static::$module, __FUNCTION__, $id,['Data yang dihapus' => $log]);

        return response()->json(['message' => 'User group deleted successfully']);
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
        $user_group = UserGroup::where(["id" => $id])->first();
        $user_group->update($data);


        // Set a session flash message
        Session::flash('success', 'Status telah diubah.');

        //Write log
        createLog(static::$module, __FUNCTION__, $id,['User Group' => $user_group, 'Statusnya diubah menjadi' => $log]);
        return response()->json(['success' => 'Status telah diubah.']);
    }

    public function checkName(Request $request){
        if($request->ajax()){
            $userGroups = UserGroup::where('name', $request->name);
            
            if(isset($request->id)){
                $userGroups->where('id', '!=', $request->id);
            }
    
            if($userGroups->exists()){
                return response()->json([
                    'message' => 'Nama sudah ada dalam database',
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
