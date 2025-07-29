<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Halaman dashboard Admin (setelah login).
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Tampilkan form edit profil Admin.
     */
    public function profile()
    {
        $admin = auth('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Proses update profil Admin.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:admins,email,{$admin->getKey()}",
        ]);

        $admin->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
