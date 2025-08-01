<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CheckoutController extends Controller
{
    /**
     * Display checkout page with modern UI and validation
     * Updated: 2025-07-31 18:48:56 by DenuJanuari
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('Unauthorized checkout access attempt', [
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => '2025-07-31 18:48:56'
                ]);

                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout');
            }

            // Get cart items with optimized query
            $cartItems = Cart::with([
                'product' => function ($query) {
                    $query->select('id', 'name', 'price', 'image_url', 'stock');
                },
                'product.images' => function ($query) {
                    $query->select('id', 'product_id', 'image_url')->limit(2);
                }
            ])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                Log::info('Checkout accessed with empty cart', [
                    'user_id' => $user->id,
                    'username' => 'DenuJanuari',
                    'timestamp' => '2025-07-31 18:48:56'
                ]);

                return redirect()->route('user.cart.index')
                    ->with('warning', 'Keranjang kosong! Silakan tambahkan produk terlebih dahulu.');
            }

            // Validate stock availability
            $stockIssues = [];
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    $stockIssues[] = "Produk '{$item->name}' tidak ditemukan";
                    continue;
                }

                if ($item->product->stock < $item->quantity) {
                    $stockIssues[] = "Stok '{$item->product->name}' tidak mencukupi (tersisa: {$item->product->stock})";
                }
            }

            if (!empty($stockIssues)) {
                return redirect()->route('user.cart.index')
                    ->with('error', 'Beberapa produk memiliki masalah stok: ' . implode(', ', $stockIssues));
            }

            // Calculate comprehensive totals
            $calculations = $this->calculateOrderTotals($cartItems);

            // Check user address
            $userAddresses = $user->addresses ?? collect();
            $hasValidAddress = $userAddresses->isNotEmpty();

            if (!$hasValidAddress) {
                Log::warning('Checkout attempted without valid address', [
                    'user_id' => $user->id,
                    'username' => 'DenuJanuari',
                    'timestamp' => '2025-07-31 18:48:56'
                ]);

                return redirect()->route('user.profile.index')
                    ->with('warning', 'Silakan lengkapi alamat pengiriman terlebih dahulu');
            }

            // Prepare shipping options
            $shippingOptions = $this->getShippingOptions($calculations['subtotal_after_discount']);

            Log::info('Checkout page accessed successfully', [
                'user_id' => $user->id,
                'username' => 'DenuJanuari',
                'cart_items_count' => $cartItems->count(),
                'subtotal' => $calculations['subtotal'],
                'total' => $calculations['total'],
                'has_promo' => !empty(session('promo_code')),
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            return view('user.checkout.index', compact(
                'cartItems',
                'calculations',
                'shippingOptions',
                'userAddresses'
            ));
        } catch (\Exception $e) {
            Log::error('Checkout page error', [
                'user_id' => Auth::id(),
                'username' => 'DenuJanuari',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            return redirect()->route('user.cart.index')
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi dalam beberapa saat.');
        }
    }

    /**
     * Create order from cart with enhanced validation and security
     * Updated: 2025-07-31 18:48:56 by DenuJanuari
     */
    public function create(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }

            // Validate request
            $validated = $request->validate([
                'shipping_method_id' => 'required|string|max:50',
                'shipping_fee' => 'required|numeric|min:0',
                'distance_km' => 'required|numeric|min:0',
                'payment_method' => 'sometimes|string|max:50',
                'notes' => 'sometimes|string|max:500'
            ]);

            DB::beginTransaction();

            // Get fresh cart items with lock
            $cartItems = Cart::where('user_id', $user->id)
                ->with(['product' => function ($query) {
                    $query->lockForUpdate();
                }])
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollback();
                return $this->handleAjaxRedirect('user.cart.index', 'Keranjang Anda kosong', false);
            }

            // Final stock validation with locks
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    DB::rollback();
                    return $this->handleAjaxRedirect('user.cart.index', "Produk '{$item->name}' tidak ditemukan", false);
                }

                if ($item->product->stock < $item->quantity) {
                    DB::rollback();
                    return $this->handleAjaxRedirect(
                        'user.cart.index',
                        "Stok '{$item->product->name}' tidak mencukupi (tersisa: {$item->product->stock})",
                        false
                    );
                }
            }

            // Calculate final totals
            $calculations = $this->calculateOrderTotals($cartItems);

            // Validate shipping fee
            if (abs($calculations['shipping_fee'] - $validated['shipping_fee']) > 0.01) {
                DB::rollback();
                Log::warning('Shipping fee mismatch detected', [
                    'user_id' => $user->id,
                    'expected' => $calculations['shipping_fee'],
                    'received' => $validated['shipping_fee'],
                    'timestamp' => '2025-07-31 18:48:56'
                ]);

                return $this->handleAjaxRedirect('user.cart.index', 'Biaya pengiriman tidak valid. Silakan muat ulang halaman.', false);
            }

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $calculations['subtotal'],
                'discount' => $calculations['discount'],
                'shipping_fee' => $validated['shipping_fee'],
                'tax' => $calculations['tax'],
                'total' => $calculations['total'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'promo_code' => session('promo_code'),
                'promo_type' => session('promo_type'),
                'promo_discount' => session('promo_discount'),
                'shipping_method' => $validated['shipping_method_id'],
                'distance_km' => $validated['distance_km'],
                'payment_method' => $validated['payment_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => 'DenuJanuari',
                'order_date' => now(),
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->price * $cartItem->quantity,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            // Clear promo session
            Session::forget(['promo_code', 'promo_type', 'promo_discount']);

            DB::commit();

            Log::info('Order created successfully', [
                'user_id' => $user->id,
                'username' => 'DenuJanuari',
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'total' => $calculations['total'],
                'items_count' => $cartItems->count(),
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            // Handle AJAX vs normal request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat! Mengarahkan ke halaman pembayaran...',
                    'redirect_url' => route('user.orders.show', $order->id),
                    'order' => [
                        'id' => $order->id,
                        'number' => $order->order_number,
                        'total' => $order->total
                    ]
                ]);
            }

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }

            return redirect()->route('user.cart.index')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Order creation failed', [
                'user_id' => Auth::id(),
                'username' => 'DenuJanuari',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token']),
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->route('user.cart.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Calculate comprehensive order totals
     * Private method for reusable calculations
     */
    private function calculateOrderTotals($cartItems)
    {
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // Calculate discount
        $discount = 0;
        if (session('promo_code')) {
            $discount = session('promo_type') === 'percent'
                ? $subtotal * (session('promo_discount', 0) / 100)
                : session('promo_discount', 0);
        }

        $subtotalAfterDiscount = max(0, $subtotal - $discount);

        // Calculate shipping (can be dynamic based on weight/distance)
        $shippingFee = $this->calculateShippingFee($subtotalAfterDiscount);

        // Calculate tax (10%)
        $tax = round($subtotalAfterDiscount * 0.1);

        // Final total
        $total = $subtotalAfterDiscount + $shippingFee + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'subtotal_after_discount' => $subtotalAfterDiscount,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'total' => $total,
            'items_count' => $cartItems->count(),
            'total_quantity' => $cartItems->sum('quantity')
        ];
    }

    /**
     * Calculate dynamic shipping fee
     */
    private function calculateShippingFee($subtotal)
    {
        // Free shipping for orders above 200k
        if ($subtotal >= 200000) {
            return 0;
        }

        // Progressive shipping rates
        if ($subtotal >= 100000) {
            return 10000;
        }

        return 15000; // Default shipping
    }

    /**
     * Get available shipping options
     */
    private function getShippingOptions($subtotal)
    {
        $options = [
            [
                'id' => 'KURIR_TOKO_SEDANG',
                'name' => 'Kurir Toko (Sedang)',
                'description' => '2-3 hari kerja',
                'price' => $this->calculateShippingFee($subtotal),
                'estimated_days' => '2-3'
            ],
            [
                'id' => 'KURIR_TOKO_CEPAT',
                'name' => 'Kurir Toko (Express)',
                'description' => '1 hari kerja',
                'price' => $this->calculateShippingFee($subtotal) + 10000,
                'estimated_days' => '1'
            ]
        ];

        return $options;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Ensure uniqueness
        $orderNumber = "AZK-{$date}-{$random}";
        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = "AZK-{$date}-{$random}";
        }

        return $orderNumber;
    }

    /**
     * Handle AJAX redirect responses
     */
    private function handleAjaxRedirect($route, $message, $success = true)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'redirect_url' => route($route)
            ]);
        }

        return redirect()->route($route)->with($success ? 'success' : 'error', $message);
    }

    /**
     * Process additional checkout steps (for future expansion)
     * Updated: 2025-07-31 18:48:56 by DenuJanuari
     */
    public function process(Request $request)
    {
        try {
            // Validate checkout session
            if (!Session::has('checkout_data')) {
                return redirect()->route('user.cart.index')
                    ->with('warning', 'Sesi checkout tidak valid. Silakan mulai dari keranjang.');
            }

            // Additional processing can be added here
            // For now, redirect to payment selection

            Log::info('Checkout process initiated', [
                'user_id' => Auth::id(),
                'username' => 'DenuJanuari',
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            return redirect()->route('user.payment.index')
                ->with('info', 'Silakan pilih metode pembayaran');
        } catch (\Exception $e) {
            Log::error('Checkout process error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-31 18:48:56'
            ]);

            return redirect()->route('user.cart.index')
                ->with('error', 'Terjadi kesalahan dalam proses checkout.');
        }
    }
}
