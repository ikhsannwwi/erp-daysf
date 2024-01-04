<?php

namespace App\Http\Controllers\member;

use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordMail;
use App\Models\admin\UserMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{
    public function index(){

        return view('member.verify.index');
    }

    public function sentLink(Request $request){
        $request->validate([
            'email' => 'required|email|exists:user_member,email',
        ]);

        $email = $request->input('email');
        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        $user = UserMember::where('email', $email)->first();

        $mailData = [
            'title' => 'Reset Password',
            'email' => $email,
            'token' => $token,
            'username' => $user->name,
            'user_kode' => $user->kode,
            'resetLink' => route('member.verified.reset', ['token' => $token]),
        ];
        Mail::to($email)->send(new ResetPasswordMail($mailData));
        return redirect(route('member.verified'))->with('success', 'Tautan berhasil dikirim melalui email');
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where('token', $token)->first();

        if (!$resetPassword) {
            abort(404);
        }

        return view('member.verify.reset', compact('resetPassword'));
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
            return redirect(route('member.password.reset', $token))->with('error', 'Email tidak sesuai');
        }
        
        $user = UserMember::where('email',$request->email)->first();
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
            $users = UserMember::where('email', $request->email);
    
            if($users->exists()){
                return response()->json([
                    'valid' => true
                ]);
            } else {
                return response()->json([
                    'message' => 'Email tidak ditemukan',
                    'valid' => false
                ]);
            }
        }
    }
}
