<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

/**
 * CartController - Enhanced version
 * Updated: 2025-08-01 12:27:13 UTC by DenuJanuari
 * - Fixed shipping options format for consistency with checkout
 * - Added missing add() method
 * - Enhanced error handling and validation
 * - Improved shipping method integration
 */
class CartController extends Controller
{
    /**
     * Display the user's shopping cart
     * Updated: 2025-08-01 12:27:13 UTC by DenuJanuari
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get cart items with product relation
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Get shipping methods using the enhanced ShippingMethod model
        // FIXED: Use getOptionsArray() method for consistency
        $shippingOptions = ShippingMethod::getOptionsArray();

        // Fallback if no shipping methods found
        if (empty($shippingOptions)) {
            Log::warning('No active shipping methods found', [
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            // Provide default shipping options as fallback
            $shippingOptions = [
                [
                    'id' => 'kurir_toko',
                    'name' => 'Kurir Toko (2-3 hari)',
                    'price' => 15000,
                    'description' => 'Pengiriman menggunakan kurir toko',
                    'service' => 'Internal',
                    'icon' => '🚛',
                    'estimated_time' => '2-3 hari'
                ],
                [
                    'id' => 'express',
                    'name' => 'Kurir Express (1 hari)',
                    'price' => 25000,
                    'description' => 'Pengiriman express cepat',
                    'service' => 'Express',
                    'icon' => '🏍️',
                    'estimated_time' => '1 hari'
                ]
            ];
        }

        // Get payment methods from database
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        // Log for debugging
        Log::info('Cart index loaded', [
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'shipping_options_count' => count($shippingOptions),
            'payment_methods_count' => $paymentMethods->count(),
            'timestamp' => '2025-08-01 12:27:13',
            'by' => 'DenuJanuari'
        ]);

        return view('user.cart.index', compact('cartItems', 'shippingOptions', 'paymentMethods'));
    }

    /**
     * Add product to cart
     * Added: 2025-08-01 12:27:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);

            // Check stock availability
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $product->stock
                ], 400);
            }

            // Check if product already exists in cart
            $existingCartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;

                // Check total quantity against stock
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total kuantitas (' . $newQuantity . ') melebihi stok yang tersedia (' . $product->stock . ')'
                    ], 400);
                }

                $existingCartItem->update(['quantity' => $newQuantity]);
                $cartItem = $existingCartItem;
            } else {
                // Create new cart item
                $cartItem = Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'name' => $product->name,
                ]);
            }

            // Update cart count in session
            $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
            session(['cart_count' => $cartCount]);

            Log::info('Product added to cart', [
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'cart_count' => $cartCount,
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount,
                'cart_item_id' => $cartItem->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding product to cart', [
                'product_id' => $request->product_id ?? null,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan produk ke keranjang'
            ], 500);
        }
    }

    /**
     * Update cart item quantity (increment or decrement)
     * Enhanced: 2025-08-01 12:27:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $action = $request->input('action');
            $cartItem = Cart::with('product')->findOrFail($id);

            // Make sure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi tidak diizinkan'
                ], 403);
            }

            $currentQuantity = $cartItem->quantity;
            $stockLimitReached = false;

            if ($action === 'increment') {
                // Check product stock before incrementing
                if ($cartItem->product && $cartItem->product->stock <= $currentQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $cartItem->product->stock
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
                        'message' => 'Jumlah minimal adalah 1. Gunakan tombol hapus untuk menghapus item.'
                    ], 400);
                }

                $cartItem->quantity -= 1;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi tidak valid. Gunakan "increment" atau "decrement".'
                ], 400);
            }

            $cartItem->save();

            // Calculate subtotal for this item
            $subtotal = $cartItem->price * $cartItem->quantity;

            // Get updated cart totals
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $cartCount = $cartItems->sum('quantity');

            // Update session with new count
            session(['cart_count' => $cartCount]);

            Log::info('Cart item updated', [
                'cart_id' => $id,
                'action' => $action,
                'new_quantity' => $cartItem->quantity,
                'cart_count' => $cartCount,
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'subtotal' => $subtotal,
                'subtotal_formatted' => 'Rp' . number_format($subtotal, 0, ',', '.'),
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp' . number_format($cartTotal, 0, ',', '.'),
                'cart_count' => $cartCount,
                'stock_limit_reached' => $stockLimitReached
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating cart item', [
                'cart_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 12:27:13',
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
     * Enhanced: 2025-08-01 12:27:13 UTC by DenuJanuari
     *
     * @param int $id
     * @return JsonResponse
     */
    public function remove($id): JsonResponse
    {
        try {
            $cartItem = Cart::findOrFail($id);

            // Make sure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi tidak diizinkan'
                ], 403);
            }

            $productName = $cartItem->name ?? ($cartItem->product->name ?? 'Produk');
            $cartItem->delete();

            // Get updated cart totals
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $cartCount = $cartItems->sum('quantity');

            // Update session with new count
            session(['cart_count' => $cartCount]);

            Log::info('Cart item removed', [
                'cart_id' => $id,
                'product_name' => $productName,
                'cart_count' => $cartCount,
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => true,
                'message' => $productName . ' berhasil dihapus dari keranjang',
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp' . number_format($cartTotal, 0, ',', '.'),
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'cart_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 12:27:13',
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
     * Enhanced: 2025-08-01 12:27:13 UTC by DenuJanuari
     *
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function clear(Request $request)
    {
        try {
            $itemCount = Cart::where('user_id', Auth::id())->count();
            Cart::where('user_id', Auth::id())->delete();

            // Reset cart count in session
            session(['cart_count' => 0]);

            Log::info('Cart cleared', [
                'user_id' => Auth::id(),
                'items_removed' => $itemCount,
                'timestamp' => '2025-08-01 12:27:13',
                'by' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil dikosongkan (' . $itemCount . ' item dihapus)',
                    'cart_count' => 0,
                    'items_removed' => $itemCount
                ]);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Keranjang belanja berhasil dikosongkan (' . $itemCount . ' item dihapus)');
        } catch (\Exception $e) {
            Log::error('Error clearing cart', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 12:27:13',
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
