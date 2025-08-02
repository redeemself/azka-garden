<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

/**
 * CartController - Database Integration for Shipping & Payment
 * 
 * Updated: 2025-08-02 03:38:02 UTC by gerrymulyadi709
 * 
 * FIXES:
 * ✅ Integration with shipping_methods table
 * ✅ Integration with payment_methods table
 * ✅ Proper data loading from database
 * ✅ Enhanced error handling with fallbacks
 */
class CartController extends Controller
{
    /**
     * Display cart items with database-driven shipping & payment options
     */
    public function index(): View|RedirectResponse
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu untuk melihat keranjang.');
            }

            $cartItems = Cart::with(['product.category', 'product.product_images'])
                ->where('user_id', Auth::id())
                ->get();

            $cartSummary = Cart::calculateTotal($cartItems);

            $invalidItems = $cartItems->filter(function ($item) {
                return !$item->hasValidProduct() || !$item->hasValidStock();
            });

            if ($invalidItems->count() > 0) {
                Log::warning('Invalid cart items found', [
                    'user_id' => Auth::id(),
                    'invalid_items' => $invalidItems->pluck('id')->toArray(),
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
            }

            // FIXED: Load shipping methods from database
            $shippingOptions = $this->getShippingMethodsFromDatabase();

            // FIXED: Load payment methods from database
            $paymentMethods = $this->getPaymentMethodsFromDatabase();

            // Add user addresses if available
            $userAddresses = [];
            try {
                if (Auth::user() && method_exists(Auth::user(), 'addresses')) {
                    $userAddresses = Auth::user()->addresses()->get() ?? collect([]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to load user addresses', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
                $userAddresses = collect([]);
            }

            // Calculate shipping estimate based on first available method
            $defaultShippingCost = $shippingOptions[0]['price'] ?? 0;
            $estimatedTotal = $cartSummary['final_total'] + $defaultShippingCost;

            return view('user.cart.index', compact(
                'cartItems',
                'cartSummary',
                'invalidItems',
                'shippingOptions',
                'paymentMethods',
                'userAddresses',
                'defaultShippingCost',
                'estimatedTotal'
            ));
        } catch (\Exception $e) {
            Log::error('Error in CartController@index: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02',
                'stack_trace' => $e->getTraceAsString()
            ]);

            // Return safe fallback data
            return view('user.cart.index', [
                'cartItems' => collect([]),
                'cartSummary' => [
                    'subtotal' => 0,
                    'total_discount' => 0,
                    'final_total' => 0,
                    'total_quantity' => 0,
                    'item_count' => 0,
                    'formatted_subtotal' => 'Rp 0',
                    'formatted_total_discount' => 'Rp 0',
                    'formatted_final_total' => 'Rp 0'
                ],
                'invalidItems' => collect([]),
                'shippingOptions' => $this->getFallbackShippingOptions(),
                'paymentMethods' => $this->getFallbackPaymentMethods(),
                'userAddresses' => collect([]),
                'defaultShippingCost' => 0,
                'estimatedTotal' => 0,
                'error' => 'Terjadi kesalahan saat memuat keranjang.'
            ]);
        }
    }

    /**
     * Get shipping methods from database
     */
    private function getShippingMethodsFromDatabase(): array
    {
        try {
            $shippingMethods = ShippingMethod::where('is_active', 1)
                ->orderBy('sort', 'asc')
                ->get();

            if ($shippingMethods->isEmpty()) {
                Log::warning('No active shipping methods found in database', [
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
                return $this->getFallbackShippingOptions();
            }

            return $shippingMethods->map(function ($method) {
                return [
                    'id' => $method->code,
                    'name' => $method->name,
                    'price' => (float) $method->cost,
                    'description' => $method->description ?? $method->service,
                    'service' => $method->service,
                    'estimated_days' => $this->getEstimatedDays($method->code),
                    'icon' => $this->getShippingIcon($method->code)
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading shipping methods from database', [
                'error' => $e->getMessage(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);
            return $this->getFallbackShippingOptions();
        }
    }

    /**
     * Get payment methods from database
     */
    private function getPaymentMethodsFromDatabase(): array
    {
        try {
            $paymentMethods = PaymentMethod::where('status', 1)
                ->orderBy('sort', 'asc')
                ->get();

            if ($paymentMethods->isEmpty()) {
                Log::warning('No active payment methods found in database', [
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
                return $this->getFallbackPaymentMethods();
            }

            return $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->code,
                    'name' => $method->name,
                    'description' => $method->description ?? '',
                    'fee' => $this->getPaymentFee($method->code),
                    'icon' => $this->getPaymentIcon($method->code),
                    'type' => $method->type
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error loading payment methods from database', [
                'error' => $e->getMessage(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);
            return $this->getFallbackPaymentMethods();
        }
    }

    /**
     * Get estimated delivery days based on shipping code
     */
    private function getEstimatedDays(string $code): string
    {
        $estimations = [
            'AMBIL_SENDIRI' => 'Langsung',
            'KURIR_TOKO_DEKAT' => 'Hari ini',
            'KURIR_TOKO' => '1-2 hari',
            'KURIR_TOKO_JAUH' => '1-3 hari',
            'JNE' => '2-3 hari',
            'JNT' => '2-4 hari',
            'SICEPAT' => '2-3 hari',
            'GOSEND' => 'Hari ini'
        ];

        return $estimations[$code] ?? '2-5 hari';
    }

    /**
     * Get shipping icon based on code
     */
    private function getShippingIcon(string $code): string
    {
        $icons = [
            'AMBIL_SENDIRI' => '🏪',
            'KURIR_TOKO_DEKAT' => '🚲',
            'KURIR_TOKO' => '🛵',
            'KURIR_TOKO_JAUH' => '🚚',
            'JNE' => '📦',
            'JNT' => '📦',
            'SICEPAT' => '⚡',
            'GOSEND' => '🏍️'
        ];

        return $icons[$code] ?? '🚚';
    }

    /**
     * Get payment fee based on code (can be customized)
     */
    private function getPaymentFee(string $code): float
    {
        // For now, all payment methods are free
        // You can customize this based on business logic
        return 0.0;
    }

    /**
     * Get payment icon based on code
     */
    private function getPaymentIcon(string $code): string
    {
        $icons = [
            'CASH' => '💵',
            'COD_QRIS' => '📱',
            'QRIS' => '📱',
            'EWALLET' => '💳'
        ];

        return $icons[$code] ?? '💳';
    }

    /**
     * Fallback shipping options if database fails
     */
    private function getFallbackShippingOptions(): array
    {
        return [
            [
                'id' => 'AMBIL_SENDIRI',
                'name' => 'Ambil Sendiri',
                'price' => 0,
                'description' => 'Ambil langsung di toko (GRATIS)',
                'estimated_days' => 'Langsung',
                'icon' => '🏪'
            ]
        ];
    }

    /**
     * Fallback payment methods if database fails
     */
    private function getFallbackPaymentMethods(): array
    {
        return [
            [
                'id' => 'CASH',
                'name' => 'Uang Tunai di Tempat',
                'description' => 'Bayar langsung secara tunai kepada kurir saat barang diterima.',
                'fee' => 0,
                'icon' => '💵'
            ]
        ];
    }

    // ... rest of the existing methods remain the same ...

    /**
     * Add product to cart - FIXED Database Schema
     */
    public function add(Request $request): JsonResponse
    {
        // Enhanced logging for debugging
        Log::info('Cart add request received', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'timestamp' => '2025-08-02 03:38:02'
        ]);

        if (!Auth::check()) {
            Log::warning('Unauthenticated cart add attempt', [
                'ip' => $request->ip(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.',
                'redirect' => route('login')
            ], 401);
        }

        // Start database transaction
        DB::beginTransaction();

        try {
            // Enhanced validation with custom error messages
            $validated = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'integer|min:1|max:999',
                'price' => 'nullable|numeric|min:0',
                'note' => 'nullable|string|max:255'
            ], [
                'product_id.required' => 'ID produk harus diisi.',
                'product_id.exists' => 'Produk tidak ditemukan.',
                'quantity.min' => 'Jumlah minimal adalah 1.',
                'quantity.max' => 'Jumlah maksimal adalah 999.',
                'price.numeric' => 'Harga harus berupa angka.',
                'note.max' => 'Catatan maksimal 255 karakter.'
            ]);

            Log::info('Cart add validation passed', [
                'validated_data' => $validated,
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            $productId = $validated['product_id'];
            $quantity = $validated['quantity'] ?? 1;

            // Get product with enhanced loading
            $product = Product::with(['category', 'product_images'])
                ->where('id', $productId)
                ->first();

            if (!$product) {
                Log::error('Product not found after validation', [
                    'product_id' => $productId,
                    'user_id' => Auth::id(),
                    'timestamp' => '2025-08-02 03:38:02'
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.'
                ], 404);
            }

            // Check if product is active
            if (!$product->status) {
                Log::warning('Attempt to add inactive product', [
                    'product_id' => $productId,
                    'user_id' => Auth::id(),
                    'timestamp' => '2025-08-02 03:38:02'
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak tersedia atau telah dihapus.'
                ], 400);
            }

            // Check stock availability
            if ($product->stock < $quantity) {
                Log::warning('Insufficient stock attempt', [
                    'product_id' => $productId,
                    'requested_quantity' => $quantity,
                    'available_stock' => $product->stock,
                    'user_id' => Auth::id(),
                    'timestamp' => '2025-08-02 03:38:02'
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi. Tersedia: ' . $product->stock
                ], 400);
            }

            $userId = Auth::id();

            // Check if product already in cart
            $existingCartItem = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingCartItem) {
                // Update existing cart item
                $newQuantity = $existingCartItem->quantity + $quantity;

                if ($newQuantity > $product->stock) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia. Stok: ' . $product->stock
                    ], 400);
                }

                $existingCartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $validated['price'] ?? $product->price,
                    'note' => $validated['note'] ?? $existingCartItem->note
                ]);

                $cartItem = $existingCartItem;
                $action = 'updated';

                Log::info('Cart item updated', [
                    'cart_item_id' => $cartItem->id,
                    'new_quantity' => $newQuantity,
                    'user_id' => $userId,
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
            } else {
                // Create new cart item
                $cartItem = Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $validated['price'] ?? $product->price,
                    'note' => $validated['note'] ?? null,
                    'interface_id' => 1 // Default user interface
                ]);

                $action = 'added';

                Log::info('New cart item created', [
                    'cart_item_id' => $cartItem->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'user_id' => $userId,
                    'timestamp' => '2025-08-02 03:38:02'
                ]);
            }

            // Get updated cart count
            $cartCount = Cart::where('user_id', $userId)->sum('quantity');

            // Get cart summary
            $cartItems = Cart::where('user_id', $userId)->get();
            $cartSummary = Cart::calculateTotal($cartItems);

            // Commit transaction
            DB::commit();

            Log::info('Product added to cart successfully', [
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'action' => $action,
                'cart_count' => $cartCount,
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => true,
                'message' => $action === 'added'
                    ? 'Produk berhasil ditambahkan ke keranjang!'
                    : 'Jumlah produk di keranjang berhasil diperbarui!',
                'action' => $action,
                'cart_count' => $cartCount,
                'cart_item_id' => $cartItem->id,
                'product_name' => $product->name,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->subtotal,
                'formatted_subtotal' => $cartItem->formatted_subtotal,
                'cart_summary' => $cartSummary,
                'data' => [
                    'cart_count' => $cartCount,
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            Log::error('Cart add validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Critical error in CartController@add', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'timestamp' => '2025-08-02 03:38:02',
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang. Silakan coba lagi.',
                'debug_info' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:999'
            ]);

            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            if (!$cartItem->hasValidProduct()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak lagi tersedia.'
                ], 400);
            }

            if ($cartItem->product->stock < $validated['quantity']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Tersedia: ' . $cartItem->product->stock
                ], 400);
            }

            $cartItem->update(['quantity' => $validated['quantity']]);

            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartSummary = Cart::calculateTotal($cartItems);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui.',
                'cart_count' => $cartCount,
                'item_subtotal' => $cartItem->subtotal,
                'formatted_item_subtotal' => $cartItem->formatted_subtotal,
                'cart_summary' => $cartSummary
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in CartController@update', [
                'error' => $e->getMessage(),
                'cart_id' => $id,
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui keranjang.'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(int $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        DB::beginTransaction();

        try {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $productName = $cartItem->product_name;
            $cartItem->delete();

            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            $cartItems = Cart::where('user_id', Auth::id())->get();
            $cartSummary = Cart::calculateTotal($cartItems);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Produk \"{$productName}\" berhasil dihapus dari keranjang.",
                'cart_count' => $cartCount,
                'cart_summary' => $cartSummary
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in CartController@remove', [
                'error' => $e->getMessage(),
                'cart_id' => $id,
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang.'
            ], 500);
        }
    }

    /**
     * Clear all cart items
     */
    public function clear(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $deletedCount = Cart::where('user_id', Auth::id())->delete();

            Log::info('Cart cleared', [
                'user_id' => Auth::id(),
                'deleted_items' => $deletedCount,
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan.',
                'cart_count' => 0,
                'deleted_items' => $deletedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CartController@clear', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang.'
            ], 500);
        }
    }

    /**
     * Get cart summary
     */
    public function getSummary(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            $cartSummary = Cart::calculateTotal($cartItems);

            return response()->json([
                'success' => true,
                'cart_summary' => $cartSummary,
                'items_count' => $cartItems->count(),
                'items' => $cartItems->map(function ($item) {
                    return $item->summary;
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CartController@getSummary', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat ringkasan keranjang.'
            ], 500);
        }
    }

    /**
     * Validate cart items
     */
    public function validateItems(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

            $validItems = $cartItems->filter(function ($item) {
                return $item->hasValidProduct() && $item->hasValidStock();
            });

            $invalidItems = $cartItems->filter(function ($item) {
                return !$item->hasValidProduct() || !$item->hasValidStock();
            });

            $stockIssues = $cartItems->filter(function ($item) {
                return $item->hasValidProduct() && !$item->hasValidStock();
            })->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'requested_quantity' => $item->quantity,
                    'available_stock' => $item->product->stock ?? 0
                ];
            });

            return response()->json([
                'success' => true,
                'valid_items_count' => $validItems->count(),
                'invalid_items_count' => $invalidItems->count(),
                'total_items' => $cartItems->count(),
                'all_valid' => $invalidItems->isEmpty(),
                'stock_issues' => $stockIssues,
                'cart_summary' => Cart::calculateTotal($validItems)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CartController@validateItems', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi keranjang.'
            ], 500);
        }
    }

    /**
     * Get shipping options from database
     */
    public function getShippingOptions(): JsonResponse
    {
        try {
            $shippingOptions = $this->getShippingMethodsFromDatabase();

            return response()->json([
                'success' => true,
                'shipping_options' => $shippingOptions
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CartController@getShippingOptions', [
                'error' => $e->getMessage(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat opsi pengiriman.',
                'shipping_options' => $this->getFallbackShippingOptions()
            ], 500);
        }
    }

    /**
     * Get payment methods from database
     */
    public function getPaymentMethods(): JsonResponse
    {
        try {
            $paymentMethods = $this->getPaymentMethodsFromDatabase();

            return response()->json([
                'success' => true,
                'payment_methods' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CartController@getPaymentMethods', [
                'error' => $e->getMessage(),
                'timestamp' => '2025-08-02 03:38:02'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat metode pembayaran.',
                'payment_methods' => $this->getFallbackPaymentMethods()
            ], 500);
        }
    }
}
