<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Developer;

class DevAuthController extends Controller
{
    /**
     * Tampilkan form login pengembang.
     */
    public function showLogin()
    {
        return view('auth.dev.login'); // Pastikan view ini ada
    }

    /**
     * Proses login pengembang menggunakan guard 'developer'.
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba autentikasi pakai guard 'developer'
        if (Auth::guard('developer')->attempt($credentials)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke dashboard dev atau route yang diinginkan
            return redirect()->intended(route('dev.dashboard'));
        }

        // Jika gagal login, kembali ke form dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Logout pengembang.
     */
    public function logout(Request $request)
    {
        Auth::guard('developer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('dev.login'));
    }

    /**
     * Tampilkan form registrasi pengembang.
     */
    public function showRegister()
    {
        return view('auth.dev.register'); // Pastikan view ini ada
    }

    /**
     * Proses registrasi pengembang baru.
     */
    public function register(Request $request)
    {
        // Validasi input registrasi
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:developers,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'dev_code' => ['required', 'string'],
        ]);

        // Validasi kode akses dev (misal hardcoded di .env atau config)
        if ($validated['dev_code'] !== config('app.dev_access_code')) {
            return back()->withErrors(['dev_code' => 'Kode akses dev salah.'])->withInput();
        }

        // Buat developer baru dengan password terenkripsi
        $developer = Developer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Login otomatis setelah registrasi berhasil
        Auth::guard('developer')->login($developer);

        // Redirect ke dashboard dev
        return redirect()->route('dev.dashboard');
    }
}
