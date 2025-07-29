<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

/**
 * OrderController
 * 
 * Updated: 2025-07-29 14:19:25 UTC by mulyadafa
 * Fixed property access issues and improved code quality
 */
class OrderController extends Controller
{
    /**
     * Pastikan hanya user terautentikasi dan lakukan validasi cart
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        // Tambahkan middleware khusus untuk checkout flow
        $this->middleware(function ($request, $next) {
            // Jika confirm tanpa orderId, pastikan ada session cart
            if ($request->route()->getName() === 'user.orders.confirm' && !$request->route('orderId')) {
                $cartItems = Cart::where('user_id', Auth::id())->count();
                if ($cartItems === 0) {
                    Log::warning('Attempted to access confirm page with empty cart', [
                        'user_id' => Auth::id(),
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'user' => 'mulyadafa'
                    ]);
                    
                    return redirect()->route('user.cart.index')
                        ->with('error', 'Keranjang Anda kosong. Silahkan tambahkan produk terlebih dahulu.');
                }
            }
            
            return $next($request);
        })->only(['confirm', 'create']);
    }

   /**
     * Tampilkan semua order milik user, dikelompokkan sesuai status (aktif, dibatalkan, kadaluarsa, selesai)
     */
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with([
                'details.product.images',
                'shipping',
                'payment.method',
                'status'
            ])
            ->orderByDesc('order_date')
            ->get();

        // Group orders by status for table display
        $getStatusValue = function($status) {
            if (is_object($status)) return $status->value;
            if (is_array($status)) return $status['value'] ?? $status[0] ?? $status;
            return $status;
        };

        $isExpired = fn($val) => strtoupper($val) === 'EXPIRED' || (isset($val) && strtolower($val) === 'expired');
        $isCanceled = fn($val) => in_array(strtoupper($val), ['CANCELED','FAILED']);
        $isPending = fn($val) => in_array(strtoupper($val), ['PENDING', 'WAITING_PAYMENT']);

        $expiredOrders = $orders->filter(function($order) use ($getStatusValue) {
            $statusVal = $getStatusValue($order->status);
            $isExpiredStatus = strtoupper($statusVal) === 'EXPIRED';
            $isExpiredPayment = isset($order->payment)
                && isset($order->payment->expired_at)
                && Carbon::parse($order->payment->expired_at)->isPast();
            return $isExpiredStatus || $isExpiredPayment;
        });

        $canceledOrders = $orders->filter(function($order) use ($getStatusValue, $isCanceled) {
            $statusVal = $getStatusValue($order->status);
            return $isCanceled($statusVal);
        });

        $activeOrders = $orders->filter(function($order) use ($getStatusValue, $isCanceled, $isExpired) {
            $statusVal = $getStatusValue($order->status);
            return !$isCanceled($statusVal) && !$isExpired($statusVal);
        });

        $pendingOrders = $activeOrders->filter(function($order) use ($getStatusValue, $isPending) {
            $statusVal = $getStatusValue($order->status);
            $isPendingStatus = $isPending($statusVal);
            $isExpiredPayment = isset($order->payment)
                && isset($order->payment->expired_at)
                && Carbon::parse($order->payment->expired_at)->isPast();
            return $isPendingStatus && !$isExpiredPayment;
        });

        $confirmedOrders = $activeOrders->filter(function($order) use ($getStatusValue, $isPending) {
            $statusVal = $getStatusValue($order->status);
            $isPendingStatus = $isPending($statusVal);
            return !$isPendingStatus;
        });

        return view('user.orders.index', [
            'orders' => $orders,
            'pendingOrders' => $pendingOrders,
            'confirmedOrders' => $confirmedOrders,
            'canceledOrders' => $canceledOrders,
            'expiredOrders' => $expiredOrders,
        ]);
    }

    /**
     * Tampilkan riwayat pesanan yang sudah selesai/dibatalkan.
     */
    public function history(): View
    {
        // Ambil hanya order yang statusnya sudah selesai/dibatalkan
        // 4 = COMPLETED, 5 = CANCELED
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('enum_order_status_id', [4, 5])
            ->with(['details.product.images', 'shipping', 'payment.method'])
            ->orderByDesc('order_date')
            ->paginate(10);

        return view('user.orders.history', [
            'orders' => $orders,
        ]);
    }

    /**
     * Tampilkan detail order.
     */
    public function show($order): View
    {
        $order = Order::where('id', $order)
            ->where('user_id', Auth::id())
            ->with([
                'details.product.images',
                'shipping',
                'payment.method',
                'status'
            ])
            ->first();

        if (!$order) {
            abort(403, 'Anda tidak berhak melihat pesanan ini.');
        }

        return view('user.orders.show', [
            'order' => $order,
        ]);
    }


    /**
     * Konfirmasi pesanan sebelum pembayaran.
     * - Jika ada orderId, tampilkan detail order.
     * - Jika tidak ada orderId (checkout belum dilakukan), tampilkan isi keranjang.
     * 
     * Updated: 2025-07-29 14:19:25 by mulyadafa - Fixed property access issues
     */
    public function confirm($orderId = null): View
    {
        // Debug output untuk logging
        Log::info('Confirm method called', [
            'order_id' => $orderId,
            'session_data' => [
                'shipping_method' => session('shipping_method'),
                'payment_method' => session('payment_method'),
                'shipping_cost' => session('shipping_cost'),
                'shipping_address_id' => session('shipping_address_id'),
                'promo_code' => session('promo_code')
            ],
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'user' => 'mulyadafa'
        ]);

        if ($orderId) {
            // Order sudah dibuat, tampilkan konfirmasi dari order
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with(['details.product.images', 'shipping', 'payment.method', 'status'])
                ->firstOrFail();
            
            // FIXED: Access total_price directly instead of using magic property final_total
            $orderTotal = isset($order->total_price) ? $order->total_price : 0;
            
            // Add tax and shipping if necessary
            if (isset($order->tax_amount)) {
                $orderTotal += $order->tax_amount;
            }
            if (isset($order->shipping_cost)) {
                $orderTotal += $order->shipping_cost;
            }
            
            Log::info('Rendering confirm view with order', [
                'order_id' => $order->id,
                'order_code' => $order->order_code ?? 'N/A',
                'total_price' => $order->total_price ?? 0,
                'user_id' => Auth::id(),
                'timestamp' => '2025-07-29 14:19:25'
            ]);
            
            return view('user.orders.confirm', compact('order'));
        } else {
            // Belum bikin order, tampilkan isi keranjang
            $cartItems = Cart::with(['product.images', 'product.category'])
                ->where('user_id', Auth::id())
                ->get();
            
            // Validasi keranjang kosong sudah ditangani di middleware
            
            // Pastikan cart_items tersedia di session untuk template yang menggunakan session
            session(['cart_items' => $cartItems]);
            
            // Ambil dan set default shipping & payment method jika belum ada
            $shipping_method = session('shipping_method', 'JNT');
            $payment_method = session('payment_method', 'CASH');
            
            // Simpan data checkout di session
            session([
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'checkout_started' => true,
                'checkout_step' => 'confirmation',
                'checkout_timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            // Set selected address jika ada di session
            $shipping_address_id = session('shipping_address_id');
            if (!$shipping_address_id && Auth::user()->addresses()->exists()) {
                // Set primary address as default if exists
                $primaryAddress = Auth::user()->addresses()->where('is_primary', 1)->first();
                if ($primaryAddress) {
                    session(['shipping_address_id' => $primaryAddress->id]);
                } else {
                    // Otherwise use the first address
                    $firstAddress = Auth::user()->addresses()->first();
                    if ($firstAddress) {
                        session(['shipping_address_id' => $firstAddress->id]);
                    }
                }
            }
            
            // Log success untuk debugging
            Log::info('Rendering confirm page with cart', [
                'cart_count' => $cartItems->count(),
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'has_shipping_address' => !empty(session('shipping_address_id')),
                'timestamp' => '2025-07-29 14:19:25',
                'user' => 'mulyadafa'
            ]);
            
            return view('user.orders.confirm', compact('cartItems'));
        }
    }

   /**
     * Cancel a draft order and return to cart page while preserving items
     */
    public function cancelDraft(Request $request): RedirectResponse
    {
        // Preserve cart by just clearing session data related to the checkout process
        $preserveCart = $request->has('preserve_cart');
        
        // Clear checkout-related session data
        if (!$preserveCart) {
            // If not preserving cart, we would clear cart items here
            // But since we want to keep them, we just clear checkout session data
        }
        
        // Clear checkout-specific session variables but keep the cart
        session()->forget([
            'checkout_started',
            'checkout_step',
            'draft_order',
            'shipping_method',     // Clear shipping method
            'payment_method',      // Clear payment method
            'shipping_address_id'  // Clear selected address
        ]);
        
        return redirect()->route('user.cart.index')
            ->with('info', 'Checkout dibatalkan. Item di keranjang tetap tersimpan.');
    }

    /**
     * Proses pembayaran pesanan (POST {order}/pay)
     */
    public function pay($order): View
    {
        $order = Order::where('id', $order)
            ->where('user_id', Auth::id())
            ->with(['details.product', 'shipping', 'payment.method'])
            ->firstOrFail();

        // Logika proses pembayaran, misal redirect ke gateway, tampil instruksi, dll
        return view('user.orders.pay', compact('order'));
    }

    /**
     * Get valid shipping costs from database sesuai shippings.sql
     */
    private function getValidShippingCosts(): array
    {
        return [
            'JNT' => 14000.00,          // ID 15 - J&T EZ sesuai database
            'GOSEND' => 25000.00,       // ID 13 - GoSend Sameday
            'JNE' => 12000.00,          // ID 14 - JNE REG
            'SICEPAT' => 15000.00,      // ID 16 - SiCepat BEST
            'KURIR_TOKO' => 15000.00,   // ID 11 - Default 5-10km
            'AMBIL_SENDIRI' => 0.00     // ID 17 - Free pickup
        ];
    }

    /**
     * Calculate shipping cost based on method and distance (for KURIR_TOKO)
     */
    private function calculateShippingCost(string $method, $selectedAddress = null): float
    {
        $costs = $this->getValidShippingCosts();
        
        // Handle KURIR_TOKO with distance-based pricing
        if ($method === 'KURIR_TOKO' && $selectedAddress) {
            // Koordinat toko Azka Garden
            $tokoLat = -6.4122794;
            $tokoLng = 106.829692;
            
            if (isset($selectedAddress->latitude) && isset($selectedAddress->longitude)) {
                $lat = (float) $selectedAddress->latitude;
                $lng = (float) $selectedAddress->longitude;
                
                // Calculate distance using Haversine formula (approximate)
                $distance = sqrt(pow($lat - $tokoLat, 2) + pow($lng - $tokoLng, 2)) * 111.32; // km
                
                // Apply tiered pricing based on distance
                if ($distance > 10.0) {
                    return 20000.00; // > 10km (ID 12 in shippings.sql)
                } elseif ($distance > 5.0) {
                    return 15000.00; // 5-10km (ID 11 in shippings.sql)
                } else {
                    return 10000.00; // < 5km (ID 10 in shippings.sql)
                }
            }
        }
        
        return (float) ($costs[$method] ?? 0.0);
    }

    /**
     * Validate shipping method and get costs
     */
    private function validateShippingMethod(string $method): array
    {
        $validShippingCosts = $this->getValidShippingCosts();
        
        if (!array_key_exists($method, $validShippingCosts)) {
            throw new \InvalidArgumentException('Metode pengiriman tidak valid.');
        }
        
        return $validShippingCosts;
    }

    /**
     * Calculate order totals
     */
    private function calculateOrderTotals($cartItems, $promotion, float $shippingCost): array
    {
        $subtotalProducts = 0.0;
        $totalDiscount = 0.0;
        $diskon_type = $promotion ? $promotion->discount_type : null;
        $diskon_value = $promotion ? $promotion->discount_value : null;
        
        foreach ($cartItems as $item) {
            $harga_satuan = (float) ($item->product->price ?? 0);
            $qty = (int) ($item->quantity ?? 0);
            $item_diskon = 0.0;
            
            if ($promotion && $diskon_type === 'percent') {
                $percent = $diskon_value ?: 10;
                $item_diskon = round($harga_satuan * ($percent / 100));
            } elseif ($promotion && $diskon_type === 'fixed' && $diskon_value) {
                $item_diskon = min($diskon_value, $harga_satuan);
            }
            
            $harga_diskon = max(0, $harga_satuan - $item_diskon);
            $subtotalProducts += $harga_diskon * $qty;
            $totalDiscount += $item_diskon * $qty;
        }

        // Calculate final totals with shipping cost
        $handlingFee = 0.0;
        $paymentFee = 0.0;
        $totalBeforeTax = $subtotalProducts + $handlingFee + $shippingCost + $paymentFee;
        $taxAmount = round($totalBeforeTax * 0.11); // PPN 11%
        $finalTotal = $totalBeforeTax + $taxAmount;

        return [
            'subtotal' => $subtotalProducts,
            'discount' => $totalDiscount,
            'handling_fee' => $handlingFee,
            'payment_fee' => $paymentFee,
            'tax_amount' => $taxAmount,
            'final_total' => $finalTotal
        ];
    }

    /**
     * Create order details from cart items
     */
    private function createOrderDetails(Order $order, $cartItems, $promotion): void
    {
        $diskon_type = $promotion ? $promotion->discount_type : null;
        $diskon_value = $promotion ? $promotion->discount_value : null;
        
        foreach ($cartItems as $item) {
            $harga_satuan = (float) ($item->product->price ?? 0);
            $qty = (int) ($item->quantity ?? 0);
            $item_diskon = 0.0;
            
            if ($promotion && $diskon_type === 'percent') {
                $percent = $diskon_value ?: 10;
                $item_diskon = round($harga_satuan * ($percent / 100));
            } elseif ($promotion && $diskon_type === 'fixed' && $diskon_value) {
                $item_diskon = min($diskon_value, $harga_satuan);
            }
            
            $harga_diskon = max(0, $harga_satuan - $item_diskon);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $qty,
                'price' => $harga_satuan,
                'discount_amount' => $item_diskon,
                'subtotal' => $harga_diskon * $qty,
                'interface_id' => 1,
            ]);
        }
    }

    /**
     * Create shipping record
     */
    private function createShippingRecord(Order $order, string $shippingMethod, float $shippingCost): void
    {
        if ($shippingMethod === 'AMBIL_SENDIRI') {
            return;
        }

        $serviceNames = [
            'JNT' => 'EZ',
            'GOSEND' => 'Sameday',
            'JNE' => 'REG',
            'SICEPAT' => 'BEST',
            'KURIR_TOKO' => 'Internal'
        ];

        DB::table('shippings')->insert([
            'order_id' => $order->id,
            'courier' => $shippingMethod,
            'service' => $serviceNames[$shippingMethod] ?? '-',
            'shipping_cost' => $shippingCost,
            'status' => $shippingMethod === 'KURIR_TOKO' ? 'WAITING_DELIVERY' : 'WAITING_PICKUP',
            'interface_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Proses checkout order dari keranjang dengan validasi shipping cost yang benar.
     * Refactored untuk mengatasi complexity issues
     */
    public function create(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->withErrors('Keranjang kosong!');
        }

        try {
            // Validate shipping method
            $shippingMethod = $request->input('shipping_method', 'JNT');
            $validShippingCosts = $this->validateShippingMethod($shippingMethod);

            // Get selected address for distance calculation (KURIR_TOKO)
            $selectedAddress = null;
            if ($request->has('shipping_address_id')) {
                $selectedAddress = $user->addresses()->where('id', $request->shipping_address_id)->first();
            }

            // Calculate shipping cost dengan validasi
            $shippingCost = $this->calculateShippingCost($shippingMethod, $selectedAddress);
            
            // Log untuk debugging
            Log::info('Order creation with shipping cost', [
                'user_id' => $user->id,
                'shipping_method' => $shippingMethod,
                'calculated_cost' => $shippingCost,
                'expected_cost' => $validShippingCosts[$shippingMethod],
                'timestamp' => '2025-07-29 14:19:25'
            ]);

            // Validate request
            $validCodes = PaymentMethod::where('status', 1)->pluck('code')->toArray();
            $request->validate([
                'payment_method' => 'required|in:' . implode(',', $validCodes),
                'shipping_method' => 'required|in:' . implode(',', array_keys($validShippingCosts)),
                'shipping_cost' => 'nullable|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            $paymentCode = $request->payment_method;
            $paymentMethod = PaymentMethod::where('code', $paymentCode)->first();

            // Validate shipping cost dari request vs calculated
            $requestShippingCost = (float) $request->input('shipping_cost', 0);
            if (abs($requestShippingCost - $shippingCost) > 0.01) {
                Log::warning('Shipping cost mismatch detected', [
                    'user_id' => $user->id,
                    'method' => $shippingMethod,
                    'calculated' => $shippingCost,
                    'request' => $requestShippingCost,
                    'difference' => abs($requestShippingCost - $shippingCost)
                ]);
                
                // Use calculated cost instead of request cost for security
                $shippingCost = $this->calculateShippingCost($shippingMethod, $selectedAddress);
            }

            // Get promo
            $promoCode = session('promo_code');
            $promotion = $promoCode ? Promotion::where('promo_code', $promoCode)->first() : null;

            // Calculate totals
            $totals = $this->calculateOrderTotals($cartItems, $promotion, $shippingCost);

            return $this->processOrderCreation(
                $user,
                $cartItems,
                $shippingMethod,
                $shippingCost,
                $paymentCode,
                $paymentMethod,
                $selectedAddress,
                $promotion,
                $totals,
                $request
            );

        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-29 14:19:25'
            ]);
            
            return back()->withErrors('Checkout gagal: ' . $e->getMessage());
        }
    }

    /**
     * Process the actual order creation (separated for complexity reduction)
     */
    private function processOrderCreation(
        $user,
        $cartItems,
        string $shippingMethod,
        float $shippingCost,
        string $paymentCode,
        $paymentMethod,
        $selectedAddress,
        $promotion,
        array $totals,
        Request $request
    ): RedirectResponse {
        DB::beginTransaction();
        
        try {
            // Extract values from totals array to avoid magic property access
            $subtotal = $totals['subtotal'] ?? 0;
            $taxAmount = $totals['tax_amount'] ?? 0;
            $discountAmount = $totals['discount'] ?? 0;
            $finalTotal = $totals['final_total'] ?? 0;
            
            // Create order with explicitly defined attributes
            $orderData = [
                'user_id' => $user->id,
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'order_date' => now(),
                'enum_order_status_id' => 1, // WAITING_PAYMENT
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount, // Fixed: Now accessing direct variable instead of array
                'discount_amount' => $discountAmount,
                'note' => $request->note,
                'payment_method' => $paymentCode,
                'shipping_method' => $shippingMethod,
                'shipping_address' => $selectedAddress ? json_encode([
                    'recipient' => $selectedAddress->recipient,
                    'full_address' => $selectedAddress->full_address,
                    'city' => $selectedAddress->city,
                    'zip_code' => $selectedAddress->zip_code,
                    'phone_number' => $selectedAddress->phone_number,
                ]) : null,
                'interface_id' => 1,
            ];
            
            $order = Order::create($orderData);

            // Create order details
            $this->createOrderDetails($order, $cartItems, $promotion);

            // Create shipping record
            $this->createShippingRecord($order, $shippingMethod, $shippingCost);

            // Create payment record
            if ($paymentMethod) {
                $order->payments()->create([
                    'method_id' => $paymentMethod->id,
                    'transaction_code' => strtoupper(uniqid('TRX')),
                    'total' => $finalTotal,
                    'enum_payment_status_id' => 1, // PENDING
                    'interface_id' => 1,
                ]);
            }

            // Clear session and cart
            session()->forget([
                'shipping_method',
                'shipping_cost',
                'payment_method',
                'shipping_address_id',
                'promo_code',
                'order_summary',
                'checkout_started',
                'checkout_step',
                'checkout_timestamp'
            ]);

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Log successful creation - use attributes from $orderData to avoid magic method access
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'user_id' => $user->id,
                'total_price' => $orderData['total_price'],
                'tax_amount' => $orderData['tax_amount'],
                'shipping_cost' => $orderData['shipping_cost'],
                'shipping_method' => $orderData['shipping_method'],
                'timestamp' => '2025-07-29 14:26:26'
            ]);

            // Process payment method
            if ($paymentCode === 'stripe') {
                return redirect()->route('stripe.checkout', ['order' => $order->id]);
            }
            
            return redirect()->route('user.orders.confirm', $order->id)
                ->with('success', 'Order berhasil dibuat! Silakan konfirmasi pesanan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Batalkan order (POST {order}/cancel dan PATCH {order}/cancel)
     */
    public function cancel($orderId): RedirectResponse
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

        $canceledId = DB::table('enum_order_status')->where('value', 'CANCELED')->value('id');
        if (!$canceledId) {
            return back()->withErrors('Status CANCELED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $canceledId;
        $order->save();

        return redirect()->route('user.orders.index')->with('success', 'Order berhasil dibatalkan!');
    }

    /**
     * Tandai pesanan sebagai selesai (PATCH {order}/complete)
     */
    public function complete($orderId): RedirectResponse
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

        $completedId = DB::table('enum_order_status')->where('value', 'COMPLETED')->value('id');
        if (!$completedId) {
            return back()->withErrors('Status COMPLETED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $completedId;
        $order->save();

        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah ditandai selesai!');
    }

    /**
     * PATCH: Tandai pesanan sebagai kadaluarsa (EXPIRED)
     */
    public function expire($orderId): RedirectResponse
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->first();

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

        $expiredId = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');
        if (!$expiredId) {
            return back()->withErrors('Status EXPIRED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        
        $order->enum_order_status_id = $expiredId;
        $order->save();

        if ($order->payment) {
            $expiredPaymentId = DB::table('enum_payment_status')->where('value', 'EXPIRED')->value('id');
            if ($expiredPaymentId) {
                $order->payment->enum_payment_status_id = $expiredPaymentId;
                $order->payment->save();
            }
        }

        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah dikadaluarsakan.');
    }

    /**
     * Batalkan konfirmasi sebelum order dibuat
     */
    public function cancelConfirm(Request $request): RedirectResponse
    {
        Cart::where('user_id', Auth::id())->delete();
        // Clear all checkout-related session data
        session()->forget([
            'shipping_method',
            'shipping_cost',
            'payment_method',
            'shipping_address_id',
            'promo_code',
            'order_summary',
            'checkout_started',
            'checkout_step',
            'checkout_timestamp',
            'cart_items'
        ]);
        
        return redirect()->route('user.cart.index')->with('success', 'Checkout dibatalkan. Semua produk di keranjang telah dihapus.');
    }

    /**
     * Tandai pesanan sebagai selesai (POST {order}/finish)
     */
    public function finish($orderId): RedirectResponse
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

        $completedId = DB::table('enum_order_status')->where('value', 'COMPLETED')->value('id');
        if (!$completedId) {
            return back()->withErrors('Status COMPLETED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $completedId;
        $order->save();

        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah ditandai selesai!');
    }

    /**
     * Bersihkan pesanan kadaluarsa, dibatalkan, dan waktu habis milik user.
     */
    public function clearExpired(Request $request)
    {
        $user = Auth::user();

        $canceledId = DB::table('enum_order_status')->where('value', 'CANCELED')->value('id');
        $expiredId  = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');

        $deleteIds = array_filter([$canceledId, $expiredId]);

        if (!empty($deleteIds)) {
            Order::where('user_id', $user->id)
                ->whereIn('enum_order_status_id', $deleteIds)
                ->delete();
        }

        DB::table('expired_orders')->where('user_id', $user->id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }
        return back()->with('success', 'Semua pesanan yang dibatalkan, kadaluarsa, dan waktu habis berhasil dibersihkan.');
    }
}