<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        $shippingOptions = [
            ['id' => 'kurir_toko', 'name' => 'Kurir Toko (2-3 hari)', 'price' => 15000],
            ['id' => 'express', 'name' => 'Kurir Express (1 hari)', 'price' => 25000]
        ];
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('user.checkout', compact('cartItems', 'shippingOptions', 'paymentMethods'));
    }
}
