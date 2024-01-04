<?php

namespace App\Http\Controllers\admin;

use DB;
use File;
use DataTables;
use Illuminate\Support\Str;
use App\Models\admin\Member;
use Illuminate\Http\Request;
use App\Models\admin\UserGroup;
use App\Models\admin\UserMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;


class MemberController extends Controller
{
    private static $module = "member";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.member.index');
    }
    
    public function getData(Request $request){
        $data = Member::query();

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
                    $btn .= '<a href="'.route('admin.member.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailMember">
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

        return view('administrator.member.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        
        $rules = [
            'nama' => 'required',
            'telepon' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'status' => 'required',
            'user_group' => 'required',
            'kode' => 'required',
        ];

        if ($request->img_url) {
            $rules['img_url'] = 'required|image';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();
            $data = Member::create([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
    
            $user = UserMember::create([
                'user_group_id' => $request->user_group,
                'kode' => $request->kode,
                'name' => $request->nama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'status' => $request->status,
                'password' => 'verify_required',
                'remember_token' => Str::random(60),
                'created_by' => auth()->user() ? auth()->user()->kode : '',
            ]);
    
            if ($request->hasFile('img_url')) {
    
                if (!empty($data->img_url)) {
                    $image_path = "./administrator/assets/media/member/" . $data->img_url;
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
    
                $image = $request->file('img_url');
                $fileName = 'IMG_' . $data->nama . '_' . date('Y-m-d-H-i-s') . '_' . uniqid(2) . '.' . $image->getClientOriginalExtension();
                $path = upload_path('member') . $fileName;
                Image::make($image->getRealPath())->save($path, 100);
                $data['img_url'] = $fileName;
                $data->save();
            }
    
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.member')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', $th->getMessage());
        }

    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Member::find($id);

        return view('administrator.member.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Member::find($id);

        $rules = [
            'nama' => 'required',
            'telepon' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'status' => 'required',
        ];

        if ($request->img_url) {
            $rules['img_url'] = 'required|image';
        }

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'updated_by' => auth()->user() ? auth()->user()->kode : '',
        ];

        if ($request->hasFile('img_url')) {

            if (!empty($data->img_url)) {
                $image_path = "./administrator/assets/media/member/" . $data->img_url;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            $image = $request->file('img_url');
            $fileName = 'IMG_' . $data->nama . '_' . date('Y-m-d-H-i-s') . '_' . uniqid(2) . '.' . $image->getClientOriginalExtension();
            $path = upload_path('member') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data['img_url'] = $fileName;
            $data->save();
        }

        if ($request->has('remove_img') && $request->remove_img == 1) {
            // dd($request->remove_img);
            // Assuming 'img_url' is the field that stores the image URL in the Member model.
        
            // Delete the image from the storage
            if (!empty($data->img_url)) {
                $image_path = "./administrator/assets/media/member/" . $data->img_url; // Update the path to your image storage directory.
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        
            // Update the database field to null or any default value as per your application's logic.
            $data['img_url'] = null; // Update this according to your field name and logic.
            $data->save();
        }
        

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.member')->with('success', 'Data berhasil diupdate.');
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
        $data = Member::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $data->toArray();

        // Delete the data.
        $data->update([
            'deleted_by' => auth()->user() ? auth()->user()->kode : '',
        ]);
        $data->delete();

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => $deletedData]);

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

        $data = Member::find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail data.',
        ]);
    }

    public function changeStatus(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "status")) {
            abort(403); 
        }
        
        $data['status'] = $request->status == "Aktif" ? 1 : 0;
        $log = $request->status == 1 ? 'Aktif' : 'Tidak Aktif';
        $id = $request->ix;
        $updates = Member::where(["id" => $id])->first();
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
    
    public function getKategori(){
        $kategori = Kategori::all();

        return response()->json([
            'kategori' => $kategori,
        ]);
    }
    
    public function checkEmail(Request $request){
        if($request->ajax()){
            $data = Member::where('email', $request->email)->withTrashed();
            
            if(isset($request->id)){
                $data->where('id', '!=', $request->id);
            }
    
            // dd($data->exists());

            if($data->exists()){
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
    
    public function checkTelepon(Request $request){
        if($request->ajax()){
            $data = Member::where('telepon', $request->telepon)->withTrashed();
            
            if(isset($request->id)){
                $data->where('id', '!=', $request->id);
            }
            // $data->get();

            // dd($data->exists());
    
            if($data->exists()){
                return response()->json([
                    'message' => 'Telepon sudah dipakai',
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

        return view('administrator.member.arsip');
    }

    public function getDataArsip(Request $request){
        $data = Member::query()->onlyTrashed();

        if ($request->status ) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            $data->get();
        }

        return DataTables::of($data)
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
            ->rawColumns(['action'])
            ->make(true);
    }

    public function restore(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "restore")) {
            abort(403);
        }
        
        $id = $request->id;
        $data = Member::withTrashed()->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        $data->restore();

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dipulihkan' => $data]);

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

        $data = Member::withTrashed()->find($id);

        if (!$data) {
            return redirect()->route('admin.member.arsip')->with('error', 'Data tidak ditemukan.');
        }

        if (!empty($data->img_url)) {
            $image_path = "./administrator/assets/media/member/" . $data->img_url;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        $data->forceDelete();

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, $data);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus secara permanent.',
        ]);
    }

    public function generateKode(){
        $generateKode = 'user-member-' . substr(uniqid(), -5);

        return response()->json([
            'generateKode' => $generateKode,
        ]);
    }

    public function getDataUserGroup(Request $request)
    {
        $data = UserGroup::query();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function checkKode(Request $request){
        if($request->ajax()){
            $users = UserMember::where('kode', $request->kode)->withTrashed();
            
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
}
