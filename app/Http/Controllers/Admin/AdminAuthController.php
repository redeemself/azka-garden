<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Tampilkan form login admin
        return view('admin.login');
    }

    public function showRegisterForm()
    {
        // Tampilkan form register admin
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:admins,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $enumRole = EnumRole::where('value', 'ADMIN')->first();
        if (! $enumRole) {
            return back()
                ->withErrors(['register_error' => 'Enum role ADMIN belum dikonfigurasi di database.'])
                ->withInput();
        }

        $adminRole = AdminRole::where('enum_admin_role_id', $enumRole->id)->first();
        if (! $adminRole) {
            return back()
                ->withErrors(['register_error' => 'Role ADMIN belum ada di tabel admin_roles.'])
                ->withInput();
        }

        try {
            $admin = Admin::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'username'     => explode('@', $validated['email'])[0],
                'password'     => Hash::make($validated['password']),
                'role_id'      => $adminRole->id,
                'last_login'   => now(),
                'interface_id' => 8, // sesuaikan jika perlu
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat admin baru: ' . $e->getMessage());
            return back()
                ->withErrors(['register_error' => 'Gagal membuat admin baru: ' . $e->getMessage()])
                ->withInput();
        }

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Registrasi admin berhasil!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $adminUser = Auth::guard('admin')->user();
            $admin = Admin::find($adminUser->id);
            if ($admin) {
                $admin->last_login = now();
                $admin->save();
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['login_error' => 'Email atau password salah'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
