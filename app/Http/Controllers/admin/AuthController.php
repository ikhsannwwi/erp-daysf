<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('administrator.authentication.login');
    }

    public function loginProses(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|string|email|max:255',
        //     'password' => 'required|min:8|max:255',
        // ]);

        // if ($validator->fails()) {
        //     // return response()->json([
        //     //     'status' => 'error',
        //     //     'message' => 'Validator tidak valid',
        //     // ],422);
        //     return back()->withErrors($validator)->withInput();
        // }
        // dd($request->email,$request->password);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika autentikasi berhasil, alihkan ke halaman yang sesuai
            return redirect()->route('admin.dashboard');
        }

        // Jika autentikasi gagal, alihkan kembali ke halaman masuk dengan pesan error
        return redirect()->route('admin.login')->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
    }
}
