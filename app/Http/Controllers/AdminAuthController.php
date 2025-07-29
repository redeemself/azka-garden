<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        // Tampilkan form login (default tab: login)
        return view('auth.admin.auth')->with(['auth_tab' => 'login']);
    }

    public function showRegister()
    {
        // Tampilkan form register (tab: register)
        return view('auth.admin.auth')->with(['auth_tab' => 'register']);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $enumRole = EnumRole::where('value', 'ADMIN')->first();
        if (!$enumRole) {
            // Gagal enum, tetap di tab register
            return back()->with([
                'register_error' => 'Enum role ADMIN belum dikonfigurasi di database.',
                'auth_tab'       => 'register'
            ])->withInput();
        }

        $adminRole = AdminRole::where('enum_admin_role_id', $enumRole->id)->first();
        if (!$adminRole) {
            return back()->with([
                'register_error' => 'Role ADMIN belum ada di tabel admin_roles.',
                'auth_tab'       => 'register'
            ])->withInput();
        }

        try {
            $admin = Admin::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'username'     => explode('@', $validated['email'])[0], // Auto username dari email
                'password'     => Hash::make($validated['password']),
                'role_id'      => $adminRole->id,
                'last_login'   => now(),
                'interface_id' => 8,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat admin baru: ' . $e->getMessage());
            return back()->with([
                'register_error' => 'Gagal membuat admin baru: ' . $e->getMessage(),
                'auth_tab'       => 'register'
            ])->withInput();
        }

        Auth::guard('admin')->login($admin);

        return redirect()->route('artikel.index')->with([
            'success'  => 'Registrasi admin berhasil!',
            'auth_tab' => 'login'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\Admin|null $admin */
            $admin = Auth::guard('admin')->user();

            if ($admin !== null) {
                $admin->last_login = now();
                $admin->save();
            } else {
                Log::error('Admin guard tidak mengembalikan model Admin valid saat login.');
            }

            return redirect()->intended(route('artikel.index'));
        }

        // Jika gagal login, tetap di tab login, error di email
        return back()->withErrors([
            'login_error' => 'Email atau password salah',
            'auth_tab'    => 'login'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('artikel.index');
    }
}
