<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;

/**
 * Fixed PaymentController - Return Type Errors Resolved
 * Updated: 2025-08-01 13:01:13 UTC by DenuJanuari
 * - Fixed return type declarations to handle both View and RedirectResponse
 * - Enhanced error handling with proper fallback views
 * - Maintained backward compatibility
 */
class PaymentController extends Controller
{
    /**
     * Display payment index page with user's payment history
     * FIXED: Return type allows both View and RedirectResponse
     * Updated: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Get user's payments with related data
            $payments = Payment::with(['order.items.product', 'paymentMethod'])
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Get payment statistics
            $paymentStats = [
                'total_payments' => Payment::whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),

                'completed_payments' => Payment::whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('status', 'completed')->count(),

                'pending_payments' => Payment::whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('status', 'pending')->count(),

                'total_amount' => Payment::whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('status', 'completed')->sum('amount')
            ];

            // Get available payment methods for new payments
            $paymentMethods = PaymentMethod::where('status', 1)
                ->orderBy('sort', 'asc')
                ->get();

            Log::info('Payment index loaded successfully', [
                'user_id' => $user->id,
                'payments_count' => $payments->count(),
                'total_payments' => $paymentStats['total_payments'],
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Try to return view, fallback gracefully if view doesn't exist
            try {
                return view('user.payment.index', compact('payments', 'paymentStats', 'paymentMethods'));
            } catch (\InvalidArgumentException $e) {
                // Fallback view if user.payment.index doesn't exist
                Log::warning('Payment index view not found, using fallback', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'timestamp' => '2025-08-01 13:01:13',
                    'by' => 'DenuJanuari'
                ]);

                return view('payment.index', compact('payments', 'paymentStats', 'paymentMethods'));
            }
        } catch (\Exception $e) {
            Log::error('Error loading payment index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Return redirect with session flash message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman pembayaran.');
        }
    }

    /**
     * Show payment details
     * FIXED: Return type allows both View and RedirectResponse
     * Updated: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Payment $payment)
    {
        try {
            $user = Auth::user();

            // Ensure user owns this payment
            if ($payment->order->user_id !== $user->id) {
                Log::warning('Unauthorized payment access attempt', [
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'payment_owner_id' => $payment->order->user_id,
                    'timestamp' => '2025-08-01 13:01:13',
                    'by' => 'DenuJanuari'
                ]);

                return redirect()->route('user.payment.index')
                    ->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
            }

            $order = $payment->order->load(['items.product', 'user']);

            Log::info('Payment details viewed', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Try to return view with fallback mechanism
            try {
                return view('user.payment.show', compact('payment', 'order'));
            } catch (\InvalidArgumentException $e) {
                // Fallback view if user.payment.show doesn't exist
                Log::warning('Payment show view not found, using fallback', [
                    'error' => $e->getMessage(),
                    'payment_id' => $payment->id,
                    'timestamp' => '2025-08-01 13:01:13',
                    'by' => 'DenuJanuari'
                ]);

                return view('payment.show', compact('payment', 'order'));
            }
        } catch (\Exception $e) {
            Log::error('Error showing payment details', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? null,
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Fallback to index route if payment route doesn't exist
            $fallbackRoute = route_exists('user.payment.index') ? 'user.payment.index' : 'cart.index';
            return redirect()->route($fallbackRoute)->with('error', 'Pembayaran tidak ditemukan.');
        }
    }

    /**
     * Display payment history with enhanced filtering
     * FIXED: Return type allows both View and RedirectResponse
     * Updated: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function history(Request $request)
    {
        try {
            $user = Auth::user();

            // Build query with filters
            $query = Payment::with(['order.items.product', 'paymentMethod'])
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });

            // Apply status filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Apply date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('payment_code', 'like', "%{$search}%")
                        ->orWhereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('order_number', 'like', "%{$search}%");
                        });
                });
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate(15);

            // Payment status options for filter
            $statusOptions = [
                'pending' => 'Menunggu Pembayaran',
                'processing' => 'Sedang Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'failed' => 'Gagal'
            ];

            Log::info('Payment history loaded', [
                'user_id' => $user->id,
                'payments_count' => $payments->count(),
                'filters' => $request->only(['status', 'date_from', 'date_to', 'search']),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Try to return view with comprehensive fallback
            try {
                return view('user.payment.history', compact('payments', 'statusOptions'));
            } catch (\InvalidArgumentException $e) {
                // Fallback to payment history view
                Log::warning('Payment history view not found, using fallback', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'timestamp' => '2025-08-01 13:01:13',
                    'by' => 'DenuJanuari'
                ]);

                try {
                    return view('payment.history', compact('payments', 'statusOptions'));
                } catch (\InvalidArgumentException $e2) {
                    // Final fallback to index view
                    return view('user.payment.index', compact('payments', 'statusOptions'));
                }
            }
        } catch (\Exception $e) {
            Log::error('Error loading payment history', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // FIXED: Safe fallback redirect
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat riwayat pembayaran.');
        }
    }

    /**
     * Process payment and create order
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'shipping_method' => 'required|string',
                'payment_method' => 'required|string',
                'cart_items' => 'required|array|min:1',
                'subtotal' => 'required|numeric|min:0',
                'shipping_cost' => 'required|numeric|min:0',
                'grand_total' => 'required|numeric|min:0',
            ]);

            $user = Auth::user();

            // Validate user has primary address
            $primaryAddress = $user->addresses()->where('is_primary', true)->first();
            if (!$primaryAddress) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu.');
            }

            // Validate cart items still exist
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang Anda kosong.');
            }

            // Validate shipping method
            $shippingMethod = ShippingMethod::active()->where('code', $request->shipping_method)->first();
            if (!$shippingMethod) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Metode pengiriman tidak valid.');
            }

            // Validate payment method
            $paymentMethod = PaymentMethod::where('status', 1)
                ->where('code', $request->payment_method)
                ->first();
            if (!$paymentMethod) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Metode pembayaran tidak valid.');
            }

            // Start database transaction
            DB::beginTransaction();

            try {
                // Create order
                $orderNumber = $this->generateOrderNumber();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'subtotal' => $request->subtotal,
                    'shipping_cost' => $request->shipping_cost,
                    'tax' => $request->tax ?? 0,
                    'discount' => $request->discount ?? 0,
                    'grand_total' => $request->grand_total,
                    'shipping_method_id' => $shippingMethod->id,
                    'shipping_method_name' => $shippingMethod->name,
                    'payment_method_id' => $paymentMethod->id,
                    'payment_method_name' => $paymentMethod->name,
                    'shipping_address' => json_encode([
                        'recipient' => $primaryAddress->recipient,
                        'phone' => $primaryAddress->phone_number,
                        'address' => $primaryAddress->full_address,
                        'city' => $primaryAddress->city,
                        'zip_code' => $primaryAddress->zip_code,
                    ]),
                    'notes' => $request->order_notes,
                    'ordered_at' => now(),
                ]);

                // Create order items
                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'product_name' => $cartItem->name ?? $cartItem->product->name,
                        'product_sku' => $cartItem->product->sku ?? null,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'total' => $cartItem->price * $cartItem->quantity,
                    ]);

                    // Update product stock
                    if ($cartItem->product) {
                        $cartItem->product->decrement('stock', $cartItem->quantity);
                    }
                }

                // Create payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method_id' => $paymentMethod->id,
                    'amount' => $request->grand_total,
                    'status' => 'pending',
                    'payment_code' => $this->generatePaymentCode(),
                    'payment_data' => json_encode($request->only([
                        'shipping_method',
                        'payment_method',
                        'order_notes'
                    ])),
                    'expires_at' => now()->addHours(24), // 24 hour expiry
                ]);

                // Clear cart
                Cart::where('user_id', $user->id)->delete();
                session(['cart_count' => 0]);

                // Commit transaction
                DB::commit();

                Log::info('Order created successfully', [
                    'order_id' => $order->id,
                    'order_number' => $orderNumber,
                    'payment_id' => $payment->id,
                    'user_id' => $user->id,
                    'total' => $request->grand_total,
                    'timestamp' => '2025-08-01 13:01:13',
                    'by' => 'DenuJanuari'
                ]);

                // Redirect based on payment method
                return $this->processPayment($payment, $paymentMethod);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error processing payment', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Process payment based on method
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Payment $payment
     * @param PaymentMethod $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    private function processPayment(Payment $payment, PaymentMethod $paymentMethod)
    {
        switch ($paymentMethod->code) {
            case 'CASH':
            case 'COD':
                $payment->update(['status' => 'pending']);
                return redirect()->route('payment.success', $payment)
                    ->with('success', 'Pesanan berhasil dibuat. Silakan siapkan uang tunai saat pengiriman.');

            case 'QRIS':
            case 'COD_QRIS':
                return $this->processQRISPayment($payment);

            case 'EWALLET':
                return $this->processEWalletPayment($payment);

            case 'BANK_TRANSFER':
                return $this->processBankTransferPayment($payment);

            default:
                return redirect()->route('payment.success', $payment)
                    ->with('success', 'Pesanan berhasil dibuat. Silakan ikuti instruksi pembayaran.');
        }
    }

    /**
     * Process QRIS payment
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     */
    private function processQRISPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'pending',
            'payment_data' => json_encode([
                'qris_code' => 'QRIS_' . $payment->payment_code,
                'qris_image' => '/images/qris/qris_' . $payment->id . '.png'
            ])
        ]);

        return redirect()->route('payment.success', $payment)
            ->with('success', 'Pesanan berhasil dibuat. Silakan scan QRIS untuk melakukan pembayaran.');
    }

    /**
     * Process e-wallet payment
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     */
    private function processEWalletPayment(Payment $payment)
    {
        return redirect()->route('payment.success', $payment)
            ->with('success', 'Pesanan berhasil dibuat. Silakan buka aplikasi e-wallet untuk menyelesaikan pembayaran.');
    }

    /**
     * Process bank transfer payment
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     */
    private function processBankTransferPayment(Payment $payment)
    {
        return redirect()->route('payment.success', $payment)
            ->with('success', 'Pesanan berhasil dibuat. Silakan transfer ke rekening yang tertera pada halaman pembayaran.');
    }

    /**
     * Payment success page
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function success(Payment $payment)
    {
        try {
            $user = Auth::user();

            // Ensure user owns this payment
            if ($payment->order->user_id !== $user->id) {
                return redirect()->route('cart.index')
                    ->with('error', 'Akses tidak diizinkan.');
            }

            $order = $payment->order->load(['items.product', 'user']);

            Log::info('Payment success page viewed', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // Try multiple view paths
            try {
                return view('user.payment.success', compact('payment', 'order'));
            } catch (\InvalidArgumentException $e) {
                return view('payment.success', compact('payment', 'order'));
            }
        } catch (\Exception $e) {
            Log::error('Error in payment success page', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan. Silakan periksa status pembayaran Anda.');
        }
    }

    /**
     * Payment cancel page
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function cancel(Payment $payment)
    {
        try {
            $user = Auth::user();

            // Ensure user owns this payment
            if ($payment->order->user_id !== $user->id) {
                return redirect()->route('cart.index')
                    ->with('error', 'Akses tidak diizinkan.');
            }

            // Mark payment as cancelled
            $payment->update(['status' => 'cancelled']);

            Log::info('Payment cancelled', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // Try multiple view paths
            try {
                return view('user.payment.cancel', compact('payment'));
            } catch (\InvalidArgumentException $e) {
                return view('payment.cancel', compact('payment'));
            }
        } catch (\Exception $e) {
            Log::error('Error in payment cancel page', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat membatalkan pembayaran.');
        }
    }

    /**
     * Payment callback handler
     * Enhanced: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        try {
            // Log callback for debugging
            Log::info('Payment callback received', [
                'data' => $request->all(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            // Process callback based on payment provider
            // This would contain actual payment gateway integration logic

            return response()->json([
                'success' => true,
                'message' => 'Callback processed successfully',
                'timestamp' => '2025-08-01 13:01:13'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing payment callback', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'timestamp' => '2025-08-01 13:01:13',
                'by' => 'DenuJanuari'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing callback',
                'timestamp' => '2025-08-01 13:01:13'
            ], 500);
        }
    }

    /**
     * Generate unique order number
     * Private: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @return string
     */
    private function generateOrderNumber()
    {
        $prefix = 'AZK';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return $prefix . $date . $random;
    }

    /**
     * Generate unique payment code
     * Private: 2025-08-01 13:01:13 UTC by DenuJanuari
     *
     * @return string
     */
    private function generatePaymentCode()
    {
        $prefix = 'PAY';
        $timestamp = now()->timestamp;
        $random = strtoupper(substr(md5(uniqid()), 0, 4));

        return $prefix . $timestamp . $random;
    }
}

/**
 * Helper function to check if route exists
 * Added: 2025-08-01 13:01:13 UTC by DenuJanuari
 */
if (!function_exists('route_exists')) {
    function route_exists($routeName)
    {
        try {
            return \Illuminate\Support\Facades\Route::has($routeName);
        } catch (\Exception $e) {
            return false;
        }
    }
}
