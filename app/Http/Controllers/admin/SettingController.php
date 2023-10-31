<?php

namespace App\Http\Controllers\admin;

use DB;
use File;
use DataTables;
use Illuminate\Http\Request;
use App\Models\admin\Setting;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    private static $module = "settings";

    public function index()
    {
        //Check permission
        if (!isAllowed(static::$module, "setting")) {
            abort(403);
        }
        $settings = Setting::get()->toArray();
        
        $settings = array_column($settings, 'value', 'name');

        // Ambil pengaturan dari database dan tampilkan di halaman
        return view('administrator.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // return $request;
        //Check permission
        if (!isAllowed(static::$module, "setting")) {
            abort(403);
        }

        

        $settings = Setting::get()->toArray();
        $settings = array_column($settings, 'value', 'name');

        
        $data_settings = [];
        $data_settings["nama_app_admin"] = $request->nama_app_admin;
        $data_settings["footer_app_admin"] = $request->footer_app_admin;
        

        if ($request->hasFile('logo_app_admin')) {
            if (array_key_exists("logo_app_admin", $settings)) {
                $imageBefore = $settings["logo_app_admin"];
                if (!empty($settings["logo_app_admin"])) {
                    $image_path = "./administrator/assets/media/settings/" . $settings["logo_app_admin"];
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $image = $request->file('logo_app_admin');
            $fileName  =  'logo_app_admin.' . $image->getClientOriginalExtension();
            $path = upload_path('settings') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data_settings['logo_app_admin'] = $fileName;
        }
        // elseif ($request->has('remove_logo_app_admin')) {
        //     if (array_key_exists("logo_app_admin", $settings) && !empty($settings["logo_app_admin"])) {
        //         $image_path = "./administrator/assets/media/settings/" . $settings["logo_app_admin"];
        //         if (File::exists($image_path)) {
        //             File::delete($image_path);
        //         }
        //         $data_settings['logo_app_admin'] = null;
        //     }
        // }

        if ($request->hasFile('favicon')) {
            if (array_key_exists("favicon", $settings)) {
                $imageBefore = $settings["favicon"];
                if (!empty($settings["favicon"])) {
                    $image_path = "./administrator/assets/media/settings/" . $settings["favicon"];
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $image = $request->file('favicon');
            $fileName  =  'favicon.' . $image->getClientOriginalExtension();
            $path = upload_path('settings') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data_settings['favicon'] = $fileName;
        }
        // elseif ($request->has('remove_favicon')) {
        //     if (array_key_exists("favicon", $settings) && !empty($settings["favicon"])) {
        //         $image_path = "./administrator/assets/media/settings/" . $settings["favicon"];
        //         if (File::exists($image_path)) {
        //             File::delete($image_path);
        //         }
        //         $data_settings['favicon'] = null;
        //     }
        // }

        if ($request->hasFile('background_login_panel_admin')) {
            if (array_key_exists("background_login_panel_admin", $settings)) {
                $imageBefore = $settings["background_login_panel_admin"];
                if (!empty($settings["background_login_panel_admin"])) {
                    $image_path = "./administrator/assets/media/settings/" . $settings["background_login_panel_admin"];
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $image = $request->file('background_login_panel_admin');
            $fileName  =  'background_login_panel_admin.' . $image->getClientOriginalExtension();
            $path = upload_path('settings') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data_settings['background_login_panel_admin'] = $fileName;
        }
        // elseif ($request->has('remove_background_login_panel_admin')) {
        //     if (array_key_exists("background_login_panel_admin", $settings) && !empty($settings["background_login_panel_admin"])) {
        //         $image_path = "./administrator/assets/media/settings/" . $settings["background_login_panel_admin"];
        //         if (File::exists($image_path)) {
        //             File::delete($image_path);
        //         }
        //         $data_settings['background_login_panel_admin'] = null;
        //     }
        // }

        

        $logs = []; // Buat array kosong untuk menyimpan log

        foreach ($data_settings as $key => $value) {
            $data = [];

            if (array_key_exists($key, $settings)) {
                $data["value"] = $value;
                $set = Setting::where('name', $key)->first();
                $set->update($data);

                $logs[] = ['---'.$key.'---' => ['Data Sebelumnya' => ['value' => $settings[$key]], 'Data terbaru' => ['value' => $value]]];
            } else {
                $data["name"] = $key;
                $data["value"] = $value;
                $set = Setting::create($data);

                $logs[] = $set;
            }
        }

        

        // Setelah perulangan selesai, $logs akan berisi semua log untuk setiap data yang diproses.


        //Write log
        createLog(static::$module, __FUNCTION__, 0,$logs);

        return redirect(route('admin.settings'))->with(['success' => 'Data berhasil di update.']);

    }
}
