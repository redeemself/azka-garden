<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Developer;
use App\Models\Role;
use App\Models\EnumRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi dengan tab role.
     */
    public function showRegister(string $role = 'user')
    {
        $role = in_array($role, ['user','admin','developer']) ? $role : 'user';
        return view('auth.register', compact('role'));
    }

    /**
     * Proses registrasi user/admin/developer berdasarkan role.
     */
    public function register(Request $request)
    {
        // 1) Validasi input umum + unik per tabel
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:user,admin,developer',
            'dev_code'   => 'sometimes|required_if:role,developer|string',
        ]);

        // Atur rule unique email sesuai role
        switch ($validated['role']) {
            case 'admin':
                $emailRule     = 'unique:admins,email';
                $model         = Admin::class;
                $guard         = 'admin';
                $interfaceId   = 8;
                break;
            case 'developer':
                $emailRule     = 'unique:developers,email';
                $model         = Developer::class;
                $guard         = 'developer';
                $interfaceId   = 11;
                break;
            default:
                $emailRule     = 'unique:users,email';
                $model         = User::class;
                $guard         = null;
                $interfaceId   = 1;
        }

        // Re‑validate email uniqueness
        $request->validate(['email' => ['required','email',$emailRule]]);

        // Peta role input ke enum_roles.value
        $map = ['user'=>'CUSTOMER','admin'=>'ADMIN','developer'=>'DEVELOPER'];
        $enum = EnumRole::where('value', $map[$validated['role']])->first();
        if (!$enum) {
            return back()->withErrors(['error'=>'Enum Role tidak ditemukan'])->withInput();
        }

        $masterRole = Role::where('enum_role_id', $enum->getKey())->first();
        if (!$masterRole) {
            $masterRole = Role::create(['enum_role_id' => $enum->getKey()]);
        }

        // Jika developer, cek kode akses
        if ($validated['role']==='developer') {
            if (($validated['dev_code'] ?? '') !== config('app.dev_access_code')) {
                return back()
                    ->withErrors(['dev_code'=>'Kode akses developer salah'])
                    ->withInput();
            }
        }

        try {
            // 2) Buat entity, password DI-HASH!
            $entity = $model::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']), // <-- PENTING!
                'last_login'   => now(),
                'interface_id' => $interfaceId,
                'username'     => $validated['role']==='user'
                                  ? null
                                  : explode('@',$validated['email'])[0],
            ]);

            // 3) Attach role(s)
            $toAttach = [];
            if ($validated['role'] === 'user') {
                $enums = EnumRole::whereIn('value',['CUSTOMER','GUEST','USER'])->get();
                foreach ($enums as $erole) {
                    $roleInstance = Role::where('enum_role_id', $erole->getKey())->first();
                    if (!$roleInstance) {
                        $roleInstance = Role::create(['enum_role_id' => $erole->getKey()]);
                    }
                    if ($roleInstance) {
                        $toAttach[] = $roleInstance->getKey();
                    }
                }
            } else {
                $toAttach[] = $masterRole->getKey();
            }
            if (!empty($toAttach)) {
                $entity->roles()->syncWithoutDetaching($toAttach);
            }

            // 4) Login otomatis
            if ($guard) {
                Auth::guard($guard)->login($entity);
            } else {
                Auth::login($entity);
            }
            $request->session()->regenerate();

        } catch (\Throwable $e) {
            Log::error("Register error [{$validated['role']}]: {$e->getMessage()}");
            return back()
                ->withErrors(['error'=>'Gagal membuat akun: '.$e->getMessage()])
                ->withInput();
        }

        // 5) Redirect setelah sukses
        return match($validated['role']) {
            'admin'     => redirect()->route('admin.dashboard')->with('success','Admin terdaftar!'),
            'developer' => redirect()->route('developer.dashboard')->with('success','Developer terdaftar!'),
            default     => redirect()->route('home')->with('success','Registrasi berhasil!'),
        };
    }
}