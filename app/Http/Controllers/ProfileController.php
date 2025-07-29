<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Role;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user beserta orders terbaru.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan relasi 'roles.enumRole', 'addresses', dan 'orders' dimuat untuk menghindari lazy loading
        $user->load('roles.enumRole', 'addresses');

        // Jika relasi orders belum dimuat atau bukan Collection, ambil dari query
        if (! $user->relationLoaded('orders') || ! $user->orders instanceof \Illuminate\Support\Collection) {
            $orders = $user->orders()->latest()->get();
        } else {
            $orders = $user->getRelation('orders');
        }

        return view('user.profile.index', compact('user', 'orders'));
    }

    /**
     * Tampilkan form edit profil.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Proses update profil user.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
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

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_photo_path = $path;
        }

        if ($request->filled('password')) {
            // Mutator di model User akan otomatis hash password
            $user->password = $request->password;
        }

        $user->save();

        Log::info("User ID {$user->id} ({$user->email}) telah memperbarui profilnya.");

        return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Konfirmasi upgrade role Guest menjadi Customer dan User.
     */
    public function confirmRoles(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $roleValues = ['CUSTOMER', 'USER'];

        $roles = Role::whereHas('enumRole', function ($query) use ($roleValues) {
            $query->whereIn('value', $roleValues);
        })->get();

        if ($roles->isEmpty()) {
            return redirect()->route('user.profile.index')->withErrors('Role Customer atau User tidak ditemukan.');
        }

        $user->roles()->syncWithoutDetaching($roles->pluck('id')->toArray());

        Log::info("User ID {$user->id} ({$user->email}) telah mengkonfirmasi peran Customer dan User.");

        return redirect()->route('user.profile.index')
                         ->with('success', 'Peran Anda berhasil diperbarui menjadi Customer dan User.');
    }
}
