<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\admin\OperatorKasir;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        if (auth()->user() || auth()->guard('operator_kasir')->user() || auth()->guard('user_member')->user()) {
            if (auth()->user()) {
                return redirect(route('admin.dashboard'));
            } else if (auth()->guard('operator_kasir')->user()) {
                return redirect(route('kasir.dashboard'));
            } else if (auth()->guard('user_member')->user()) {
                return redirect(route('member.dashboard'));
            }
        }

        return view('administrator.authentication.login');
    }

    public function loginProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Admin login successful
            return redirect()->route('admin.dashboard');
        } else if (Auth::guard('operator_kasir')->attempt($credentials)) {
            // Operator Kasir login successful
            return redirect()->route('kasir.dashboard');
        } else if (Auth::guard('user_member')->attempt($credentials)) {
            // Operator Kasir login successful
            return redirect()->route('member.dashboard');
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
                Auth::logout();
                return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('operator_kasir')->user()) {
                Auth::guard('operator_kasir')->logout();
                return redirect()->route('kasir.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('user_member')->user()) {
                Auth::guard('user_member')->logout();
                return redirect()->route('member.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            }
        }
    }
}
