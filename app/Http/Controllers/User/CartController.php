<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\PaymentMethod;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil item keranjang user
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Dummy shipping options (bisa diganti dengan model ShippingMethod jika ada)
        $shippingOptions = [
            [
                'id' => 'kurir_toko',
                'name' => 'Kurir Toko (2-3 hari)',
                'price' => 15000
            ],
            [
                'id' => 'express',
                'name' => 'Kurir Express (1 hari)',
                'price' => 25000
            ]
        ];

        // Ambil payment method dari database
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('user.cart', compact('cartItems', 'shippingOptions', 'paymentMethods'));
    }

    // Tambahkan fungsi add, update, remove, dll jika diperlukan.
}
