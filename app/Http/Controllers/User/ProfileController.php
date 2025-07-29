<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Role;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan halaman profil user (frontend).
     */
    public function index()
    {
        $user = Auth::user()->load('roles.enumRole', 'addresses');
        $orders = $user->orders()->latest()->get();

        $createdDate = ($user && $user->getAttribute('created_at'))
            ? Carbon::parse($user->getAttribute('created_at'))->format('d M Y')
            : null;

        // Kirimkan semua role ke view agar $allRoles tersedia
        $allRoles = Role::all();

        return view('user.profile.index', compact('user', 'orders', 'createdDate', 'allRoles'));
    }

    /**
     * Form edit profil user.
     */
    public function edit()
    {
        $user = Auth::user();

        $createdDate = ($user && $user->getAttribute('created_at'))
            ? Carbon::parse($user->getAttribute('created_at'))->format('d M Y')
            : null;

        $allRoles = Role::all();

        return view('user.profile.edit', compact('user', 'createdDate', 'allRoles'));
    }

    /**
     * Update profil user (termasuk upload gambar profile).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
            'roles' => 'required|array|max:3',
            'roles.*' => 'exists:roles,id',
            'current_password' => [
                'nullable',
                'required_with:password',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value && !Hash::check($value, $user->getAuthPassword())) {
                        $fail('Password sekarang salah.');
                    }
                },
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Simpan foto profil jika ada
        if ($request->hasFile('profile_image')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_photo_path = $path;
        }

        // Update password dan plain_password jika diberikan
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
            $user->plain_password = $request->input('password'); // DEV/TESTING ONLY
        }

        // Update role user, maksimal 3 peran
        $user->roles()->sync($validated['roles']);

        $user->save();

        return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
