<?php

namespace App\Http\Controllers\admin;

use PDF;
use DataTables;
use Carbon\Carbon;
use App\Models\admin\Log;
use App\Models\admin\User;
use App\Models\admin\Module;
use Illuminate\Http\Request;
use App\Models\admin\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class LogSystemController extends Controller
{
    private static $module = "log_system";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.logs.index');
    }

    public function getData(Request $request)
    {
        $data = Log::query()->with('user');

        if ($request->user || $request->module) {
            if ($request->user != "") {
                $user = $request->user;
                $data->where("user_id", $user);
            }
            
            if ($request->module != "") {
                $module = $request->module ;
                $data->where("module", $module);
            }
            $data->get();
        }
        // dd($request->module);


        return DataTables::of($data)
            ->make(true);
    }

    public function getDetail($id){

        $data = Log::with('user')->find($id);
        if (!$data) {
            return abort(404);
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    
    public function getDataModule(Request $request)
    {
        $data_module = Module::query();

        return DataTables::of($data_module)
            ->make(true);
    }

    public function getDataUser(Request $request)
    {
        $data_user = User::query()->with('user_group');

        return DataTables::of($data_user)
            ->make(true);
    }

    public function clearLogs()
    {
        // Check permission
        if (!isAllowed(static::$module, "clear")) {
            abort(403);
        }

        try {
            // Hitung tanggal tujuh hari yang lalu
            $DaysAgo = Carbon::now()->subDays(7);

            // Hapus data log yang lebih lama dari 7 hari kebelakang
            Log::where('created_at', '<', $DaysAgo)->delete();

            return redirect()->route('admin.logSystems')->with('success', 'Data log yang lebih lama dari 7 hari berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.logSystems')->with('error', 'Terjadi kesalahan saat menghapus data log: ' . $e->getMessage());
        }
    }

    public function generatePDF()
    {
        ini_set('max_execution_time', 600); // Set the maximum execution time to 600 seconds (5 minutes)

        $data = Log::with('user')->orderBy('created_at', 'desc')->get();

        $settings = Setting::get()->toArray();
        $settings = array_column($settings, 'value', 'name');

        // Render the view using Laravel's View class
        $html = View::make('administrator.logs.export', compact('data'))->render();

        // Configure PDF settings (optional)
        $pdf = PDF::loadHTML($html);

        // Output the PDF (open in browser)
        try {
            return $pdf->stream('log-export.pdf');
        } catch (\Exception $e) {
            return $e->getMessage(); // Output any error message to help diagnose the problem
        }
    }
}
