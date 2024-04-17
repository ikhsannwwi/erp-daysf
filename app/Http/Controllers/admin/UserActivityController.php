<?php

namespace App\Http\Controllers\admin;

use DataTables;
use Carbon\Carbon;
use App\Models\admin\User;
use Illuminate\Http\Request;
use App\Models\admin\UserGroup;
use App\Http\Controllers\Controller;

class UserActivityController extends Controller
{
    private static $module = "user_activity";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.user_activity.index');
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
                if ($row->is_online === 1 && $row->last_seen >= now()->subMinutes(5) || $row->is_online === 0 && $row->last_seen >= now()->subMinutes(2)) {
                    $status = '<span class="badge bg-success">Online</span></div>';
                } else {
                    $status = '<span class="badge bg-danger">Offline</span></div>';
                }
                return $status;
            })
            ->addColumn('lastseen', function ($row) {
                $lastseen = Carbon::parse($row->last_seen)->diffForHumans();
                
                return $lastseen;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailUser">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'lastseen', 'action'])
            ->make(true);
    }

    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = User::with('user_group')->with('profile')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail user.',
        ]);
    }

    public function getUserGroup(){
        $usergroup = UserGroup::all();

        return response()->json([
            'usergroup' => $usergroup,
        ]);
    }
}
