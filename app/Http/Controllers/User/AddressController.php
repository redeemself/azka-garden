<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    /**
     * Simpan alamat baru untuk user yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|max:20',
            'recipient' => 'required|max:50',
            'phone_number' => 'required|max:20',
            'full_address' => 'required|max:200',
            'city' => 'required|max:50',
            'zip_code' => 'required|max:10',
        ]);

        $address = new Address();
        $address->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $address->label = $request->label;
        $address->recipient = $request->recipient;
        $address->phone_number = $request->phone_number;
        $address->full_address = $request->full_address;
        $address->city = $request->city;
        $address->zip_code = $request->zip_code;
        $address->is_primary = $request->is_primary ?? 0;
        $address->interface_id = 1; // atau bisa sesuai kebutuhan aplikasi Anda
        $address->save();

        return redirect()->back()->with('success', 'Alamat berhasil disimpan.');
    }

    /**
     * (Opsional) Tampilkan daftar alamat milik user untuk keperluan edit/hapus.
     */
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $addresses = [];
        if ($user) {
            $addresses = Address::where('user_id', $user->id)->get();
        }
        return view('user.address.index', compact('addresses'));
    }

    /**
     * Update koordinat latitude & longitude alamat user via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoords(Request $request)
    {
        $address = Address::find($request->id);
        if ($address && $address->user_id == optional(\Illuminate\Support\Facades\Auth::user())->id) {
            $address->latitude = $request->latitude;
            $address->longitude = $request->longitude;
            $address->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}