<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod; // Add this import

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
        }

        // Get shipping methods from database, same as CartController
        $shippingOptions = ShippingMethod::active()->ordered()->get()->map(function ($method) {
            return [
                'id' => $method->code,
                'name' => $method->display_name,
                'description' => $method->description,
                'price' => $method->cost,
                'service' => $method->service
            ];
        })->toArray();

        $paymentMethods = PaymentMethod::where('status', 1)->get();

        // Validate that the requested shipping method exists
        $requestedShippingMethod = $request->get('shipping_method');
        if ($requestedShippingMethod) {
            $shippingMethodExists = collect($shippingOptions)->firstWhere('id', $requestedShippingMethod);
            if (!$shippingMethodExists) {
                return redirect()->route('cart.index')->with('error', 'Metode pengiriman tidak valid');
            }
        }

        // Validate that the requested payment method exists
        $requestedPaymentMethod = $request->get('payment_method');
        if ($requestedPaymentMethod) {
            $paymentMethodExists = $paymentMethods->firstWhere('code', $requestedPaymentMethod);
            if (!$paymentMethodExists) {
                return redirect()->route('cart.index')->with('error', 'Metode pembayaran tidak valid');
            }
        }

        return view('user.checkout', compact('cartItems', 'shippingOptions', 'paymentMethods'));
    }
}
