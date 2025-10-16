<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function ShowFormLogin(){
        return view('auth.login');
    }

    public function login(Request $request){
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Proses autentikasi
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk mencegah session fixation
            $request->session()->regenerate();
            
            // Redirect ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Jika login gagal, kembali ke form login dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request){
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
