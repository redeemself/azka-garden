<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
=======
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PaymentMethod;
use Carbon\Carbon;
<<<<<<< HEAD
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

/**
 * OrderController
 * 
 * Updated: 2025-07-29 15:12:41 UTC by mulyadafa
 * Fixed property access issues and improved checkout flow
 */
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
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

    public function index(): View
=======

class OrderController extends Controller
{
    /**
     * Pastikan hanya user terautentikasi
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan semua order milik user, dikelompokkan sesuai status (aktif, dibatalkan, kadaluarsa, selesai)
     */
    public function index()
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
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

<<<<<<< HEAD
=======
        // Group orders by status for table display
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
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

<<<<<<< HEAD
    public function history(): View
    {
=======
    /**
     * Tampilkan riwayat pesanan yang sudah selesai/dibatalkan.
     */
    public function history()
    {
        // Ambil hanya order yang statusnya sudah selesai/dibatalkan
        // 4 = COMPLETED, 5 = CANCELED
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('enum_order_status_id', [4, 5])
            ->with(['details.product.images', 'shipping', 'payment.method'])
            ->orderByDesc('order_date')
            ->paginate(10);

        return view('user.orders.history', [
            'orders' => $orders,
        ]);
    }

<<<<<<< HEAD
    public function show($order): View
=======
    /**
     * Tampilkan detail order.
     */
    public function show($order)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
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

<<<<<<< HEAD
    public function confirm($orderId = null): View
    {
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
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with(['details.product.images', 'shipping', 'payment.method', 'status'])
                ->firstOrFail();
            $orderTotal = isset($order->total_price) ? $order->total_price : 0;
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
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            return view('user.orders.confirm', compact('order'));
        } else {
            $cartItems = Cart::with(['product.images', 'product.category'])
                ->where('user_id', Auth::id())
                ->get();
            session(['cart_items' => $cartItems]);
            $shipping_method = session('shipping_method', 'JNT');
            $payment_method = session('payment_method', 'CASH');
            session([
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'checkout_started' => true,
                'checkout_step' => 'confirmation',
                'checkout_timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            $shipping_address_id = session('shipping_address_id');
            if (!$shipping_address_id && Auth::user()->addresses()->exists()) {
                $primaryAddress = Auth::user()->addresses()->where('is_primary', 1)->first();
                if ($primaryAddress) {
                    session(['shipping_address_id' => $primaryAddress->id]);
                } else {
                    $firstAddress = Auth::user()->addresses()->first();
                    if ($firstAddress) {
                        session(['shipping_address_id' => $firstAddress->id]);
                    }
                }
            }
            Log::info('Rendering confirm page with cart', [
                'cart_count' => $cartItems->count(),
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'has_shipping_address' => !empty(session('shipping_address_id')),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user' => 'mulyadafa'
            ]);
=======
    /**
     * Konfirmasi pesanan sebelum pembayaran.
     * - Jika ada orderId, tampilkan detail order.
     * - Jika tidak ada orderId (checkout belum dilakukan), tampilkan isi keranjang.
     */
    public function confirm($orderId = null)
    {
        if ($orderId) {
            // Order sudah dibuat, tampilkan konfirmasi dari order
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->with(['details.product', 'shipping', 'payment.method', 'status'])
                ->firstOrFail();
            return view('user.orders.confirm', compact('order'));
        } else {
            // Belum bikin order, tampilkan isi keranjang
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
            return view('user.orders.confirm', compact('cartItems'));
        }
    }

<<<<<<< HEAD
    public function cancelDraft(Request $request): RedirectResponse
    {
        $preserveCart = $request->has('preserve_cart');
=======
    /**
     * Cancel a draft order and return to cart page while preserving items
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelDraft(Request $request)
    {
        // Preserve cart by just clearing session data related to the checkout process
        $preserveCart = $request->has('preserve_cart');
        
        // Clear checkout-related session data
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        if (!$preserveCart) {
            // If not preserving cart, we would clear cart items here
            // But since we want to keep them, we just clear checkout session data
        }
<<<<<<< HEAD
=======
        
        // Clear checkout-specific session variables but keep the cart
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        session()->forget([
            'checkout_started',
            'checkout_step',
            'draft_order',
<<<<<<< HEAD
            'shipping_method',
            'payment_method',
            'shipping_address_id'
        ]);
=======
            'shipping_method',     // Clear shipping method
            'payment_method',      // Clear payment method
            'shipping_address_id'  // Clear selected address
        ]);
        
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        return redirect()->route('user.cart.index')
            ->with('info', 'Checkout dibatalkan. Item di keranjang tetap tersimpan.');
    }

<<<<<<< HEAD
    public function pay($order): View
=======
    /**
     * Proses pembayaran pesanan (POST {order}/pay)
     */
    public function pay($order)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
    {
        $order = Order::where('id', $order)
            ->where('user_id', Auth::id())
            ->with(['details.product', 'shipping', 'payment.method'])
            ->firstOrFail();
<<<<<<< HEAD
        return view('user.orders.pay', compact('order'));
    }

    public function checkoutSuccess($orderId): RedirectResponse
    {
        Log::info('Checkout success page accessed', [
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->route('user.orders.confirm', $orderId);
    }

    private function getValidShippingCosts(): array
    {
        return [
            'JNT' => 14000.00,
            'GOSEND' => 25000.00,
            'JNE' => 12000.00,
            'SICEPAT' => 15000.00,
            'KURIR_TOKO' => 15000.00,
            'AMBIL_SENDIRI' => 0.00
        ];
    }

    private function calculateShippingCost(string $method, $selectedAddress = null): float
    {
        $costs = $this->getValidShippingCosts();
        if ($method === 'KURIR_TOKO' && $selectedAddress) {
            $tokoLat = -6.4122794;
            $tokoLng = 106.829692;
            if (isset($selectedAddress->latitude) && isset($selectedAddress->longitude)) {
                $lat = (float) $selectedAddress->latitude;
                $lng = (float) $selectedAddress->longitude;
                $distance = sqrt(pow($lat - $tokoLat, 2) + pow($lng - $tokoLng, 2)) * 111.32;
                if ($distance > 10.0) {
                    return 20000.00;
                } elseif ($distance > 5.0) {
                    return 15000.00;
                } else {
                    return 10000.00;
                }
            }
        }
        return (float) ($costs[$method] ?? 0.0);
    }

    private function validateShippingMethod(string $method): array
    {
        $validShippingCosts = $this->getValidShippingCosts();
        if (!array_key_exists($method, $validShippingCosts)) {
            throw new \InvalidArgumentException('Metode pengiriman tidak valid.');
        }
        return $validShippingCosts;
    }

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
            'shipping_cost' => $shippingCost,
            'tax_amount' => $taxAmount,
            'total' => $finalTotal
        ];
    }

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

    public function create(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->withErrors('Keranjang kosong! Silakan tambahkan produk terlebih dahulu.');
        }
        try {
            $shippingMethod = $request->input('shipping_method', 'JNT');
            $validShippingCosts = $this->validateShippingMethod($shippingMethod);
            $selectedAddress = null;
            if ($request->has('shipping_address_id')) {
                $selectedAddress = $user->addresses()->where('id', $request->shipping_address_id)->first();
            }
            if (!$selectedAddress && $shippingMethod !== 'AMBIL_SENDIRI') {
                Log::warning('Checkout failed: No shipping address', [
                    'user_id' => $user->id,
                    'shipping_method' => $shippingMethod,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                return redirect()->route('user.addresses.index')
                    ->withErrors('Alamat pengiriman wajib diisi. Silakan tambahkan alamat terlebih dahulu.');
            }
            $shippingCost = $this->calculateShippingCost($shippingMethod, $selectedAddress);
            Log::info('Order creation with shipping cost', [
                'user_id' => $user->id,
                'shipping_method' => $shippingMethod,
                'calculated_cost' => $shippingCost,
                'expected_cost' => $validShippingCosts[$shippingMethod] ?? 0,
                'address_id' => $selectedAddress ? $selectedAddress->id : null,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            $validCodes = PaymentMethod::where('status', 1)->pluck('code')->toArray();
            $validationRules = [
                'payment_method' => 'required|in:' . implode(',', $validCodes),
                'shipping_method' => 'required|in:' . implode(',', array_keys($validShippingCosts)),
                'shipping_cost' => 'nullable|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ];
            if ($shippingMethod !== 'AMBIL_SENDIRI') {
                $validationRules['shipping_address_id'] = 'required|exists:addresses,id,user_id,' . $user->id;
            }
            $validatedData = $request->validate($validationRules);

            $paymentCode = $request->payment_method;
            $paymentMethod = PaymentMethod::where('code', $paymentCode)->first();
            if (!$paymentMethod) {
                throw new \Exception('Metode pembayaran tidak valid atau tidak tersedia.');
            }
            $requestShippingCost = (float) $request->input('shipping_cost', 0);
            if (abs($requestShippingCost - $shippingCost) > 0.01) {
                Log::warning('Shipping cost mismatch detected', [
                    'user_id' => $user->id,
                    'method' => $shippingMethod,
                    'calculated' => $shippingCost,
                    'request' => $requestShippingCost,
                    'difference' => abs($requestShippingCost - $shippingCost),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                $shippingCost = $this->calculateShippingCost($shippingMethod, $selectedAddress);
            }
            $promoCode = session('promo_code');
            $promotion = $promoCode ? Promotion::where('promo_code', $promoCode)->first() : null;
            $totals = $this->calculateOrderTotals($cartItems, $promotion, $shippingCost);
            $requestTotal = (float) $request->input('total', 0);
            if (abs($requestTotal - $totals['total']) > 1) {
                Log::warning('Total mismatch detected', [
                    'user_id' => $user->id,
                    'calculated' => $totals['total'],
                    'request' => $requestTotal,
                    'difference' => abs($requestTotal - $totals['total']),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
            }
            DB::beginTransaction();
            try {
                $result = $this->processOrderCreation(
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
                DB::commit();
                return $result;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Order processing failed within transaction', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            $errorMessage = app()->environment('production')
                ? 'Checkout gagal. Silakan coba lagi atau hubungi layanan pelanggan.'
                : 'Checkout gagal: ' . $e->getMessage();
            return back()->withErrors($errorMessage);
        }
    }

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
            $subtotal = $totals['subtotal'] ?? 0;
            $taxAmount = $totals['tax_amount'] ?? 0;
            $discountAmount = $totals['discount'] ?? 0;
            $finalTotal = $totals['total'] ?? 0;
            $orderData = [
                'user_id' => $user->id,
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'order_date' => now(),
                'enum_order_status_id' => 1,
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
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
            $this->createOrderDetails($order, $cartItems, $promotion);
            $this->createShippingRecord($order, $shippingMethod, $shippingCost);
=======

        // Logika proses pembayaran, misal redirect ke gateway, tampil instruksi, dll
        return view('user.orders.pay', compact('order'));
    }

    /**
     * Proses checkout order dari keranjang.
     * Metode ini HARUS bernama 'create' agar sesuai dengan route POST user.orders.create
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->withErrors('Keranjang kosong!');
        }

        // Validasi metode pembayaran dari database
        $validCodes = PaymentMethod::where('status', 1)->pluck('code')->toArray();
        $request->validate([
            'payment_method' => 'required|in:' . implode(',', $validCodes),
        ]);
        $paymentCode = $request->payment_method;
        $paymentMethod = PaymentMethod::where('code', $paymentCode)->first();

        // Cek kode promo
        $promoCode = session('promo_code') ?? null;
        $promotion = $promoCode ? Promotion::where('promo_code', $promoCode)->first() : null;
        $diskon_type = $promotion->discount_type ?? null;
        $diskon_value = $promotion->discount_value ?? null;

        // Hitung total
        $total = 0;
        foreach ($cartItems as $item) {
            $harga_satuan = $item->product->price ?? 0;
            $qty = $item->quantity ?? 0;
            $item_diskon = 0;
            if ($promotion && $diskon_type === 'percent') {
                $percent = $diskon_value ?: 10;
                $item_diskon = round($harga_satuan * ($percent / 100));
            } elseif ($promotion && $diskon_type === 'fixed' && $diskon_value) {
                $item_diskon = $diskon_value;
            }
            $harga_diskon = max(0, $harga_satuan - $item_diskon);
            $total += $harga_diskon * $qty;
        }

        DB::beginTransaction();
        try {
            // Buat order baru
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'order_date' => now(),
                'enum_order_status_id' => 1, // WAITING_PAYMENT
                'total_price' => $total,
                'shipping_cost' => 0,
                'note' => $request->note,
                'payment_method' => $paymentCode,
                'interface_id' => 1,
            ]);

            // Buat order_details
            foreach ($cartItems as $item) {
                $harga_satuan = $item->product->price ?? 0;
                $qty = $item->quantity ?? 0;
                $item_diskon = 0;
                if ($promotion && $diskon_type === 'percent') {
                    $percent = $diskon_value ?: 10;
                    $item_diskon = round($harga_satuan * ($percent / 100));
                } elseif ($promotion && $diskon_type === 'fixed' && $diskon_value) {
                    $item_diskon = $diskon_value;
                }
                $harga_diskon = max(0, $harga_satuan - $item_diskon);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $qty,
                    'price' => $harga_satuan,
                    'subtotal' => $harga_diskon * $qty,
                    'interface_id' => 1,
                ]);
            }

            // Buat relasi ke tabel payments (SESUAI MIGRASI)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
            if ($paymentMethod) {
                $order->payments()->create([
                    'method_id' => $paymentMethod->id,
                    'transaction_code' => strtoupper(uniqid('TRX')),
<<<<<<< HEAD
                    'total' => $finalTotal,
                    'enum_payment_status_id' => 1,
                    'interface_id' => 1,
                ]);
            }
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
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'user_id' => $user->id,
                'total_price' => $orderData['total_price'],
                'tax_amount' => $orderData['tax_amount'],
                'shipping_cost' => $orderData['shipping_cost'],
                'shipping_method' => $orderData['shipping_method'],
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            if ($paymentCode === 'stripe') {
                return redirect()->route('stripe.checkout', ['order' => $order->id]);
            }
            return redirect()->route('user.orders.checkout.success', $order->id)
                ->with('success', 'Order berhasil dibuat! Silakan konfirmasi pesanan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancel($orderId): RedirectResponse
=======
                    'total' => $order->total_price + $order->shipping_cost,
                    'enum_payment_status_id' => 1, // PENDING
                    'interface_id' => 1,
                ]);
            }

            // Kosongkan keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Lanjutkan proses sesuai metode pembayaran
            if ($paymentCode == 'stripe') {
                // Redirect/integrasi Stripe
                return redirect()->route('stripe.checkout', ['order' => $order->id]);
            }
            // Untuk metode lokal, tampilkan halaman konfirmasi pesanan!
            return redirect()->route('user.orders.confirm', $order->id)
                ->with('success', 'Order berhasil dibuat! Silakan konfirmasi pesanan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Checkout gagal: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan order (POST {order}/cancel dan PATCH {order}/cancel)
     * Ubah status order menjadi CANCELED agar tampil di tabel pesanan dibatalkan
     */
    public function cancel($orderId)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
<<<<<<< HEAD
        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }
=======

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $canceledId = DB::table('enum_order_status')->where('value', 'CANCELED')->value('id');
        if (!$canceledId) {
            return back()->withErrors('Status CANCELED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $canceledId;
        $order->save();
<<<<<<< HEAD
        return redirect()->route('user.orders.index')->with('success', 'Order berhasil dibatalkan!');
    }

    public function complete($orderId): RedirectResponse
=======

        return redirect()->route('user.orders.index')->with('success', 'Order berhasil dibatalkan!');
    }

    /**
     * Tandai pesanan sebagai selesai (PATCH {order}/complete)
     */
    public function complete($orderId)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
<<<<<<< HEAD
        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }
=======

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $completedId = DB::table('enum_order_status')->where('value', 'COMPLETED')->value('id');
        if (!$completedId) {
            return back()->withErrors('Status COMPLETED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $completedId;
        $order->save();
<<<<<<< HEAD
        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah ditandai selesai!');
    }

    public function expire($orderId): RedirectResponse
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('payment')
            ->first();
        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }
=======

        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah ditandai selesai!');
    }

    /**
     * PATCH: Tandai pesanan sebagai kadaluarsa (EXPIRED)
     * Sekarang juga mengubah status payment menjadi EXPIRED jika ada relasi.
     */
    public function expire($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('payment') // pastikan relasi payment ikut diambil
            ->first();

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $expiredId = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');
        if (!$expiredId) {
            return back()->withErrors('Status EXPIRED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
<<<<<<< HEAD
        $order->enum_order_status_id = $expiredId;
        $order->save();
=======
        // Update status order
        $order->enum_order_status_id = $expiredId;
        $order->save();

        // Update status payment (opsional, best practice)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        if ($order->payment) {
            $expiredPaymentId = DB::table('enum_payment_status')->where('value', 'EXPIRED')->value('id');
            if ($expiredPaymentId) {
                $order->payment->enum_payment_status_id = $expiredPaymentId;
                $order->payment->save();
            }
        }
<<<<<<< HEAD
        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah dikadaluarsakan.');
    }

    public function cancelConfirm(Request $request): RedirectResponse
    {
        Cart::where('user_id', Auth::id())->delete();
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

    public function finish($orderId): RedirectResponse
=======

        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah dikadaluarsakan.');
    }

    /**
     * Batalkan konfirmasi sebelum order dibuat (hapus semua produk di keranjang, kembali ke keranjang)
     */
    public function cancelConfirm(Request $request)
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.cart.index')->with('success', 'Checkout dibatalkan. Semua produk di keranjang telah dihapus.');
    }

    /**
     * Tandai pesanan sebagai selesai (POST {order}/finish)
     */
    public function finish($orderId)
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
<<<<<<< HEAD
        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }
=======

        if (!$order) {
            abort(403, 'Order tidak ditemukan atau anda tidak berhak.');
        }

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        $completedId = DB::table('enum_order_status')->where('value', 'COMPLETED')->value('id');
        if (!$completedId) {
            return back()->withErrors('Status COMPLETED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        $order->enum_order_status_id = $completedId;
        $order->save();
<<<<<<< HEAD
        return redirect()->route('user.orders.index')->with('success', 'Pesanan telah ditandai selesai!');
    }

    public function clearExpired(Request $request)
    {
        $user = Auth::user();
        $canceledId = DB::table('enum_order_status')->where('value', 'CANCELED')->value('id');
        $expiredId  = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');
        $deleteIds = array_filter([$canceledId, $expiredId]);
=======

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

        // Hapus orders pada tabel 'orders' yang status dibatalkan/expired milik user
>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        if (!empty($deleteIds)) {
            Order::where('user_id', $user->id)
                ->whereIn('enum_order_status_id', $deleteIds)
                ->delete();
        }
<<<<<<< HEAD
        DB::table('expired_orders')->where('user_id', $user->id)->delete();
=======

        DB::table('expired_orders')->where('user_id', $user->id)->delete();

>>>>>>> 8f1c5a7 (Initial commit: add azka-garden project)
        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }
        return back()->with('success', 'Semua pesanan yang dibatalkan, kadaluarsa, dan waktu habis berhasil dibersihkan.');
    }
}