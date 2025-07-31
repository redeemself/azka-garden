<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     * Updated: 2025-07-31 14:06:50 by DenuJanuari
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu');
            }

            // Check if user has items in cart
            $cartItems = Cart::with(['product', 'product.images'])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->count() == 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang kosong! Silakan tambahkan produk terlebih dahulu.');
            }

            // Check if checkout data exists in session
            $hasCheckoutData = Session::has('checkout_shipping_method');

            if (!$hasCheckoutData) {
                \Log::warning('Checkout page accessed without proper data', [
                    'user_id' => $user->id,
                    'session_keys' => array_keys(Session::all()),
                    'timestamp' => '2025-07-31 14:06:50'
                ]);

                return redirect()->route('cart.index')
                    ->with('warning', 'Silakan pilih metode pengiriman terlebih dahulu di halaman keranjang.');
            }

            \Log::info('Checkout page accessed successfully', [
                'user_id' => $user->id,
                'cart_items_count' => $cartItems->count(),
                'timestamp' => '2025-07-31 14:06:50',
                'user' => 'DenuJanuari'
            ]);

            return view('user.checkout', compact('cartItems'));
        } catch (\Exception $e) {
            \Log::error('Checkout page error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 14:06:50'
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Process checkout (placeholder for future implementation)
     */
    public function process(Request $request)
    {
        // This method can be used for additional checkout processing
        // For now, redirect to payment
        return redirect()->route('user.payment.index');
    }
}
