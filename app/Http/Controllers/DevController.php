<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use App\Models\Role;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DevController extends Controller
{
    public function __construct()
    {
        // Hanya guest developer yang boleh akses register & login
        $this->middleware('guest:developer')->except(['dashboard', 'logout']);
        // Developer harus login untuk akses dashboard dan logout
        $this->middleware('auth:developer')->only(['dashboard', 'logout']);
    }

    // Tampilkan form register developer
    public function showRegister()
    {
        return view('auth.register', ['role' => 'developer']);
    }

    // Proses register developer baru
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:developers,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:developer',
        ]);

        $devEnumRole = EnumRole::where('value', 'DEVELOPER')->first();
        if (!$devEnumRole) {
            abort(500, 'Enum role DEVELOPER belum dikonfigurasi di database.');
        }

        $devRole = Role::where('enum_role_id', $devEnumRole->id)->first();
        if (!$devRole) {
            abort(500, 'Role DEVELOPER belum ada di tabel roles.');
        }

        try {
            $developer = Developer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => explode('@', $data['email'])[0],
                'password' => Hash::make($data['password']),
                'role_id' => $devRole->id,
                'last_login' => now(),
                // tambahkan field lain sesuai tabel developers
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat developer baru: ' . $e->getMessage());
            abort(500, 'Gagal membuat developer baru.');
        }

        Auth::guard('developer')->login($developer);

        return redirect()->route('dev.dashboard')->with('success', 'Registrasi developer berhasil!');
    }

    // Tampilkan form login developer
    public function showLogin()
    {
        return view('auth.login', ['role' => 'developer']);
    }

    // Proses login developer
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('developer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var Developer $developer */
            $developer = Auth::guard('developer')->user();
            $developer->last_login = now();
            $developer->save();

            return redirect()->intended(route('dev.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // Tampilkan dashboard developer
    public function dashboard()
    {
        return view('dev.dashboard');
    }

    // Logout developer
    public function logout(Request $request)
    {
        Auth::guard('developer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dev.login');
    }
}
