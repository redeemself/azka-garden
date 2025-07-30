<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the user's addresses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = [];
        if ($user) {
            $addresses = Address::where('user_id', $user->id)->get();
        }
        return view('user.address.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.address.create');
    }

    /**
     * Store a newly created address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:20',
            'recipient' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string|max:200',
            'city' => 'required|string|max:50',
            'zip_code' => 'required|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $address = new Address($validated);
        $address->user_id = Auth::id();

        // If this is the first address, make it primary
        if (Address::where('user_id', Auth::id())->count() === 0) {
            $address->is_primary = true;
        } else {
            $address->is_primary = $request->is_primary ?? false;
        }

        $address->interface_id = 1; // atau bisa sesuai kebutuhan aplikasi Anda
        $address->save();

        return redirect()->route('user.address.index')->with('success', 'Alamat berhasil disimpan.');
    }

    /**
     * Display the specified address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\View\View
     */
    public function show(Address $address)
    {
        // Verify that the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.address.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\View\View
     */
    public function edit(Address $address)
    {
        // Verify that the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.address.edit', compact('address'));
    }

    /**
     * Update the specified address in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Address $address)
    {
        // Verify that the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'label' => 'required|string|max:20',
            'recipient' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string|max:200',
            'city' => 'required|string|max:50',
            'zip_code' => 'required|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $address->fill($validated);
        $address->is_primary = $request->is_primary ?? $address->is_primary;
        $address->save();

        return redirect()->route('user.address.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Remove the specified address from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Address $address)
    {
        // Verify that the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // If deleting a primary address and other addresses exist, make another one primary
        if ($address->is_primary) {
            $otherAddress = Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->first();
            if ($otherAddress) {
                $otherAddress->is_primary = true;
                $otherAddress->save();
            }
        }

        $address->delete();

        return redirect()->route('user.address.index')->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set an address as primary.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPrimary(Address $address)
    {
        // Verify that the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Reset all addresses to non-primary
        Address::where('user_id', Auth::id())
            ->update(['is_primary' => 0]);

        // Set the selected address as primary
        $address->is_primary = 1;
        $address->save();

        return redirect()->route('user.address.index')->with('success', 'Alamat utama berhasil diubah.');
    }

    /**
     * Update coordinates (latitude & longitude) for an address via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCoords(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:addresses,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $address = Address::find($request->id);
        if ($address && $address->user_id == Auth::id()) {
            $address->latitude = $request->latitude;
            $address->longitude = $request->longitude;
            $address->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized or address not found']);
    }
}
