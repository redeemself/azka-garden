<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except(['dashboard', 'logout']);
        $this->middleware('auth:admin')->only(['dashboard', 'logout']);
    }

    public function showRegister()
    {
        return view('auth.register', ['role' => 'admin']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin',
        ]);

        $adminEnumRole = EnumRole::where('value', 'ADMIN')->first();
        if (!$adminEnumRole || !isset($adminEnumRole->id)) {
            abort(500, 'Enum role ADMIN belum dikonfigurasi atau properti id tidak ditemukan.');
        }

        $adminRole = Role::where('enum_role_id', $adminEnumRole->id)->first();
        if (!$adminRole || !isset($adminRole->id)) {
            abort(500, 'Role ADMIN belum ada di tabel roles atau properti id tidak ditemukan.');
        }

        try {
            $admin = Admin::create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'username'   => explode('@', $data['email'])[0],
                'password'   => Hash::make($data['password']),
                'role_id'    => $adminRole->id,
                'last_login' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat admin baru: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal membuat admin baru'])->withInput();
        }

        Auth::guard('admin')->login($admin);

        return redirect()->route('artikel.index')->with('success', 'Registrasi admin berhasil!');
    }

    public function showLogin()
    {
        return view('auth.login', ['role' => 'admin']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var Admin|null $admin */
            $admin = Auth::guard('admin')->user();
            if ($admin !== null) {
                $admin->last_login = now();
                $admin->save();
            } else {
                Log::error('Admin guard tidak mengembalikan model Admin valid saat login.');
            }

            return redirect()->intended(route('artikel.index'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('artikel.index'); // pastikan view artikel/index.blade.php ada
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('artikel.index'); // arahkan ke artikel.index setelah logout
    }
}
