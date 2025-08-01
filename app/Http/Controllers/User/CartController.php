<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get cart items with product relation
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Dummy shipping options (can be replaced with ShippingMethod model if exists)
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

        // Get payment methods from database
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('user.cart', compact('cartItems', 'shippingOptions', 'paymentMethods'));
    }

    /**
     * Update cart item quantity (increment or decrement)
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $action = $request->input('action');
            $cartItem = Cart::with('product')->findOrFail($id);

            // Make sure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $currentQuantity = $cartItem->quantity;
            $stockLimitReached = false;

            if ($action === 'increment') {
                // Check product stock before incrementing
                if ($cartItem->product && $cartItem->product->stock <= $currentQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk tidak mencukupi'
                    ], 400);
                }

                $cartItem->quantity += 1;

                // Check if reached stock limit after update
                if ($cartItem->product && $cartItem->product->stock <= $cartItem->quantity) {
                    $stockLimitReached = true;
                }
            } elseif ($action === 'decrement') {
                // Prevent quantity from going below 1
                if ($currentQuantity <= 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah minimal adalah 1'
                    ], 400);
                }

                $cartItem->quantity -= 1;
            }

            $cartItem->save();

            // Calculate subtotal for this item
            $subtotal = $cartItem->price * $cartItem->quantity;

            // Get updated cart total
            $cartTotal = Cart::where('user_id', Auth::id())->get()
                ->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

            // Get updated cart count (sum of all quantities)
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

            // Update session with new count
            session(['cart_count' => $cartCount]);

            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'subtotal' => $subtotal,
                'subtotal_formatted' => number_format($subtotal, 0, ',', '.'),
                'cart_total' => $cartTotal,
                'cart_total_formatted' => number_format($cartTotal, 0, ',', '.'),
                'cart_count' => $cartCount,
                'stock_limit_reached' => $stockLimitReached
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating cart item', [
                'cart_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 05:01:32',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui keranjang'
            ], 500);
        }
    }

    /**
     * Remove an item from the cart
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);

            // Make sure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $cartItem->delete();

            // Get updated cart total
            $cartTotal = Cart::where('user_id', Auth::id())->get()
                ->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

            // Get updated cart count (sum of all quantities)
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

            // Update session with new count
            session(['cart_count' => $cartCount]);

            return response()->json([
                'success' => true,
                'cart_total' => $cartTotal,
                'cart_total_formatted' => number_format($cartTotal, 0, ',', '.'),
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'cart_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 05:01:32',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item dari keranjang'
            ], 500);
        }
    }

    /**
     * Clear all items from the user's cart
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function clear(Request $request)
    {
        try {
            Cart::where('user_id', Auth::id())->delete();

            // Reset cart count in session
            session(['cart_count' => 0]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil dikosongkan',
                    'cart_count' => 0
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Keranjang belanja berhasil dikosongkan');
        } catch (\Exception $e) {
            Log::error('Error clearing cart', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 05:01:32',
                'by' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengosongkan keranjang'
                ], 500);
            }

            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat mengosongkan keranjang');
        }
    }
}
