<?php

namespace App\Http\Controllers\Auth; // <-- Namespace diubah ke App\Http\Controllers\Auth

use App\Http\Controllers\Controller; // Tetap gunakan base Controller dari App\Http\Controllers
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan formulir login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi input dari formulir
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan

            // Redirect ke halaman admin/dashboard setelah login berhasil
            return redirect()->intended('/admin');
        }

        // Jika autentikasi gagal, kembali ke formulir login dengan pesan error
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna

        $request->session()->invalidate(); // Invalidasi session
        $request->session()->regenerateToken(); // Regenerasi token CSRF

        return redirect('/'); // Redirect ke halaman beranda
    }
}