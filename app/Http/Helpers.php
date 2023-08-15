<?php

use App\Models\User;
use App\Models\Module;
use App\Models\admin\Log;
use App\Models\UserGroup;
use Illuminate\Support\Str;
use App\Models\ModuleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserGroupPermission;

function asset_administrator($url)
{
	return asset('administrator/' . $url);
}

function asset_frontpage($url)
{
	return asset('frontpage/' . $url);
}

function createLog($module, $action, $data_id)
{
	$log['ip_address'] 	= request()->ip();
	$log['user_id'] 	= auth()->check() ? auth()->user()->id : 1;
	$log['module'] 		= $module;
	$log['action'] 		= $action;
	$log['data_id'] 	= $data_id;
	$log['created_at'] 	= date('Y-m-d H:i:s');
	Log::create($log);
}

function isAllowed($modul, $modul_akses)
{
	$data_user = User::find(auth()->user()->id);
	$grup_pengguna_id = $data_user->user_group_id;
	$permission = getPermissionGroup($grup_pengguna_id);
	if ($grup_pengguna_id == 0) {
		return TRUE;
	} else {
		$group = UserGroup::find($grup_pengguna_id);
        
        if ($group->status == 1) {
            $permission = getPermissionGroup($grup_pengguna_id);
            
            if ($permission[$grup_pengguna_id][$modul][$modul_akses] == 1) {
                return true; // Jika user group aktif dan memiliki izin, berikan akses
            }
        }
    }
    return false; // Default, jika tidak memenuhi syarat maka tidak diizinkan akses
	
}

function getDefaultPermission()
{
	$query = ModuleAccess::select(DB::raw("
    module_access.*,
    user_group_permissions.user_group_id,
    user_group_permissions.status"))
		->leftJoin(
			DB::raw("user_group_permissions"),
			function ($join) {
				$join->on('user_group_permissions.module_access_id', '=', 'module_access.id');
			}
		);
	$data_akses = $query->get();
	$data_grup_pengguna = UserGroup::all();
	$permission = array();
	foreach ($data_grup_pengguna as $val) {
		foreach ($data_akses as $row) {
			$permission[$val->id][$row->module_id][$row->id] = 0;
		}
	}
	return $permission;
}

function getPermissionGroup($user_group_id)
{
	$data_akses = ModuleAccess::select(DB::raw('
    modules.identifiers as module_identifiers,
    module_access.*,
    user_group_permissions.user_group_id,
    user_group_permissions.status'))
		->leftJoin(
			DB::raw("user_group_permissions"),
			function ($join) use ($user_group_id) {
				$join->on('user_group_permissions.module_access_id', '=', 'module_access.id')
                ->where("user_group_permissions.user_group_id", "=", $user_group_id);
			}
		)
		->leftJoin(DB::raw("modules"), "modules.id", "=", "module_access.module_id")
		->get();
	$permission = [];
	$index = 0;

	foreach ($data_akses as $row) {
		if ($row->status == "") {
			$status = 0;
		} else {
			$status = $row->status;
		}
		$permission[$user_group_id][$row->module_identifiers][$row->identifiers] = $status;
	}
	$index++;

	return $permission;
}

function getPermissionGroup2($x)
{
	$data_akses = ModuleAccess::select(DB::raw('
    modules.identifiers as module_identifiers,
    module_access.*,
    user_group_permissions.user_group_id,
    user_group_permissions.status'))
		->leftJoin(
			DB::raw("user_group_permissions"),
			function ($join) use ($x) {
				$join->on('user_group_permissions.module_access_id', '=', 'module_access.id')
                ->where("user_group_permissions.user_group_id", "=", $x);
			}
		)
		->leftJoin(DB::raw("modules"), "modules.id", "=", "module_access.module_id")
		->get();
        // dd($x);
	$permission = [];
	$index = 0;
	foreach ($data_akses as $row) {
		if ($row->status == "") {
			$status = 0;
		} else {
			$status = $row->status;
		}
		$permission[$x][$row->module_identifiers][$row->identifiers] = $status;
	}
	$index++;
	return $permission;
}

function getPermissionModuleGroup()
{
	$data_user = User::find(auth()->user()->id);
	$grup_pengguna_id = $data_user->user_group_id;
	$data_akses = ModuleAccess::select(DB::raw('
    modules.identifiers as module_identifiers, 
    COUNT(user_group_permissions.id) as permission_given'))
		->leftJoin(
			DB::raw("user_group_permissions"),
			function ($join) use ($grup_pengguna_id) {
				$join->on('user_group_permissions.module_access_id', '=', 'module_access.id')
                ->where("user_group_permissions.user_group_id", "=", $grup_pengguna_id)
                ->where("user_group_permissions.status", 1);
			}
		)
		->leftJoin(DB::raw("modules"), "modules.id", "=", "module_access.module_id")
		->groupBy("modules.id")
		->get();

	$permission = [];
	$index = 0;

	foreach ($data_akses as $row) {
		if ($row->permission_given > 0) {
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		$permission[$row->module_identifiers] = $status;
	}
	$index++;

	return $permission;
}

function showModule($module, $permission_module)
{
	$data_user = User::find(auth()->user()->id);
	$grup_pengguna_id = $data_user->user_group_id;
	if ($grup_pengguna_id == 0) {
		return TRUE;
	} else {
		if (array_key_exists($module, $permission_module)) {
			return $permission_module[$module];
		} else {
			return FALSE;
		}
	}
}
