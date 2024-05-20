<?php

namespace App\Http\Controllers\admin;

use DB;
use App\Models\Karyawan;
use App\Models\admin\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\admin\Setting;
use App\Models\ResetPassword;
use App\Models\admin\UserMember;
use App\Models\admin\OperatorKasir;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMailAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Rules\EmailExistsInMultipleTables;

class AuthController extends Controller
{
    public function login(){
        if (auth()->user() || auth()->guard('operator_kasir')->user() || auth()->guard('user_member')->user()) {
            if (auth()->user()) {
                return redirect(route('admin.dashboard'))->with('success', 'Anda sudah login.');
            } else if (auth()->guard('operator_kasir')->user()) {
                return redirect(route('kasir.dashboard'))->with('success', 'Anda sudah login.');
            } else if (auth()->guard('user_member')->user()) {
                return redirect(route('member.dashboard'))->with('success', 'Anda sudah login.');
            }
        }

        return view('administrator.authentication.login');
    }

    public function loginProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Admin login successful
            User::where('kode', Auth::user()->kode)->update(['is_online' => 1]);
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login.');
        } else if (Auth::guard('operator_kasir')->attempt($credentials)) {
            // Operator Kasir login successful
            return redirect()->route('kasir.dashboard')->with('success', 'Berhasil Login.');
        } else if (Auth::guard('user_member')->attempt($credentials)) {
            // Operator Kasir login successful
            return redirect()->route('member.dashboard')->with('success', 'Berhasil Login.');
        }
        
        $userMember = UserMember::where('password', 'verify_required')->where('email', $request->email)->first();
        if (!empty($userMember)) {
            return redirect()->route('member.verified');
        }

        // Jika autentikasi gagal, alihkan kembali ke halaman masuk dengan pesan error
        return redirect()->route('admin.login')->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        if (auth()->user() || auth()->guard('operator_kasir')->user() || auth()->guard('user_member')->user()) {
            if (auth()->user()) {
                User::where('kode', Auth::user()->kode)->update(['is_online' => 0]);
                Auth::logout();
                return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('operator_kasir')->user()) {
                Auth::guard('operator_kasir')->logout();
                return redirect()->route('kasir.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('user_member')->user()) {
                Auth::guard('user_member')->logout();
                return redirect()->route('member.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            }
        }else{
            return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
        }
    }

    public function email(){
        return view('administrator.authentication.reset.email');
    }

    public function sendlink(Request $request){
        $request->validate([
            'email' => ['required', 'email', new EmailExistsInMultipleTables],
        ]);

        $email = $request->input('email');
        $token = Str::random(64);

        try {
            DB::beginTransaction();
            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                ['token' => $token, 'created_at' => now()]
            );
    
            $user = User::where('email', $email)->first() ??
                    UserMember::where('email', $email)->first() ??
                    OperatorKasir::where('email', $email)->first() ??
                    Karyawan::where('email', $email)->first();

    
            $settings = Setting::get()->toArray();
            $settings = array_column($settings, 'value', 'name');
    
            // Set a default value if 'general_nama_app' key is not present
            $generalNamaApp = isset($settings['general_nama_app']) ? $settings['general_nama_app'] : 'ERP Daysf';
            
            $mailData = [
                'title' => '['. ($generalNamaApp ? $generalNamaApp : 'ERP Daysf') .'] Reset Password',
                'email' => $email,
                'token' => $token,
                'username' => $user->name,
                'user_kode' => $user->kode,
                'resetLink' => route('admin.accounts.reset', $token),
            ];
            DB::commit();
            Mail::to($email)->send(new ResetPasswordMailAdmin($mailData));
            return redirect(route('admin.accounts.reset.email'))->with('success', 'Tautan berhasil dikirim melalui email');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where('token', $token)->first();

        if (!$resetPassword) {
            abort(404);
        }

        return view('administrator.authentication.reset.reset', compact('resetPassword'));
    }

    public function updatePassword(Request $request, $token){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
        ]);

        $resetPassword = ResetPassword::where('token', $token)
                                  ->where('email', $request->input('email'))
                                  ->first();

        if (!$resetPassword) {
            return redirect(route('admin.accounts.reset', $token))->with('error', 'Email tidak sesuai');
        }
        $email = $request->email;
        
        $user = User::where('email', $email)->first() ??
                    UserMember::where('email', $email)->first() ??
                    OperatorKasir::where('email', $email)->first() ??
                    Karyawan::where('email', $email)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ]);

        // Hapus token dari tabel reset password
        ResetPassword::where('token', $token)
            ->where('email', $request->input('email'))
            ->delete();

        return redirect()->route('admin.login')->with('success', 'Password has been reset successfully.');
    }

    public function checkEmail(Request $request){
        if($request->ajax()){
            $email = $request->email;
    
            $userWithEmail = User::where('email', $email);
            $userMemberWithEmail = UserMember::where('email', $email);
            $operatorKasirWithEmail = OperatorKasir::where('email', $email);
            $KaryawanWithEmail = Karyawan::where('email', $email);
    
            $userExists = $userWithEmail->exists() || $userMemberWithEmail->exists() || $operatorKasirWithEmail->exists() || $KaryawanWithEmail->exists();
    
            if($userExists){
                return response()->json([
                    'valid' => true
                ]);
            } else {
                return response()->json([
                    'message' => 'Email tidak ditemukan!',
                    'valid' => false
                ]);
            }
        }
    }
}
