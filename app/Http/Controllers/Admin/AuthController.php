<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan form login admin
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Redirect ke dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // Jika gagal login, kembalikan ke form dengan error
        return back()
            ->withErrors(['login_error' => 'Email atau password salah'])
            ->withInput();
    }

    // Tampilkan form registrasi admin
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    // Proses registrasi admin baru
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:admins,email',
            'password'               => 'required|string|confirmed|min:8',
        ]);

        // Cari enum role 'ADMIN' dari tabel enum_roles
        $enumRole = EnumRole::where('value', 'ADMIN')->firstOrFail();

        // Cari admin role terkait dari tabel admin_roles berdasarkan enum_admin_role_id
        $adminRole = AdminRole::where('enum_admin_role_id', $enumRole->id)->firstOrFail();

        // Buat admin baru dengan data valid dan role yang sesuai
        $admin = Admin::create([
            'username'     => Str::before($data['email'], '@'),
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role_id'      => $adminRole->id,
            'status_id'    => 1,
            'interface_id' => 8,
            'last_login'   => now(),
        ]);

        // Login otomatis setelah registrasi berhasil
        Auth::guard('admin')->login($admin);

        // Redirect ke dashboard admin dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    // Proses logout admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
