<?php

namespace App\Http\Controllers\admin;

use DataTables;
use Illuminate\Support\Str;
use App\Models\admin\Module;
use Illuminate\Http\Request;
use App\Models\admin\ModuleAccess;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    private static $module = "module_management";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.module.index');
    }
    
    public function getData(Request $request){
        $data = Module::query()->with('access');

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.module.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailModule">
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

        return view('administrator.module.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        // Validasi input dari form
        $request->validate([
            'name' => 'required|string',
            'identifiers' => 'required|string',
            'modul_akses.*.tipe' => 'required|string',
            'modul_akses.*.kode_akses' => 'required_if:modul_akses.*.tipe,element|string',
            'modul_akses.*.kode_akses' => 'required_if:modul_akses.*.tipe,page|string',
        ]);

        // Simpan data modul akses ke dalam database
        $module = Module::Create([
            'name' => Str::ucfirst($request->name),
            'identifiers' => Str::lower($request->identifiers),
        ]);

        // Simpan data modul akses terkait (modul_akses) ke dalam database
        foreach ($request->input('modul_akses') as $modulAkses) {
            $module->access()->create([
                'module_id' => $module->id,
                'name' => Str::ucfirst($modulAkses['kode_akses']),
                'identifiers' => Str::lower($modulAkses['kode_akses']),
            ]);
        }
    

        $module_access = ModuleAccess::where('module_id',$module->id)->get();

        createLog(static::$module, __FUNCTION__, $module->id, ['Data yang disimpan' => ['Modul' => $module, 'Modul Akses' => $module_access]]);
        return redirect()->route('admin.module')->with('success', 'Data berhasil disimpan.');
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Module::find($id);

        return view('administrator.module.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission for updating
        if (!isAllowed(static::$module, "update")) {
            abort(403);
        }

        // Validate input from the form
        $request->validate([
            'name' => 'required|string',
            'identifiers' => 'required|string',
            'modul_akses.*.tipe' => 'required|string',
            'modul_akses.*.kode_akses' => 'required_if:modul_akses.*.tipe,element|string',
            'modul_akses.*.kode_akses' => 'required_if:modul_akses.*.tipe,page|string',
        ]);

        $id = $request->id;
        // Find the module by ID
        $module = Module::find($id);

        // Check if the module exists
        if (!$module) {
            return redirect()->route('admin.module')->with('error', 'Modul tidak ditemukan.');
        }
        $log_module_before = $module;

        $module_access = ModuleAccess::where('module_id', $module->id)->get();

        $log_module_access_after = $module_access;

        // Update the module data
        $module->update([
            'name' => $request->name,
            'identifiers' => $request->identifiers,
        ]);

        // Delete existing module access records for this module
        $module->access()->delete();

        // Save the updated module access data
        foreach ($request->input('modul_akses') as $modulAkses) {
            $module->access()->create([
                'module_id' => $module->id,
                'name' => Str::ucfirst($modulAkses['kode_akses']),
                'identifiers' => Str::lower($modulAkses['kode_akses']),
            ]);
        }

        $log_module_access = ModuleAccess::where('module_id', $module->id)->get();

        createLog(static::$module, __FUNCTION__, $module->id, [
            'Data sebelum diupdate' => ['Modul' => $log_module_before, 'Modul Akses' => $log_module_access_after],
            'Data sesudah diupdate' => ['Modul' => $module, 'Modul Akses' => $log_module_access],
        ]);

        return redirect()->route('admin.module')->with('success', 'Data berhasil diupdate.');
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
        $data = Module::findorfail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Delete the user.
        $data->delete();

        $logAccess = ModuleAccess::where('module_id',$id)->get();

        if (!empty($data->access())) {
            // Check if the modul$modulAkses is being force-deleted
            $data->access()->delete();
        }

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => ['Module' => $data, 'Module Access' => $logAccess]]);

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

        $data = Module::findOrFail($id);
        $access = ModuleAccess::where('module_id',$id)->get();

        return response()->json([
            'data' => $data,
            'access' => $access,
            'status' => 'success',
            'message' => 'Sukses memuat detail module.',
        ]);
    }
}
