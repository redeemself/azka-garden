<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan import Auth facade

class UserAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); // Pastikan view ada di resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        // Validasi input login user
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi dengan guard 'web' (user biasa)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect ke route user.home (dashboard user atau halaman beranda user)
            return redirect()->intended(route('user.home'));
        }

        // Jika gagal login, kembali ke halaman login dengan error
        return back()->withErrors([
            'email' => 'Login gagal, email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
