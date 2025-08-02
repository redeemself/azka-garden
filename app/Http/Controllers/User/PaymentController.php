<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Order;
use App\Models\EnumPaymentStatus; // Using the model-based enum, not the PHP enum

/**
 * Payment Controller
 *
 * Updated: 2025-08-02 00:50:22
 * By: gerrymulyadi709
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of the user's payments
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::with('order')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform payment status
        foreach ($payments as $payment) {
            $enumStatus = EnumPaymentStatus::find($payment->enum_payment_status_id);
            $payment->status = $enumStatus ? strtolower($enumStatus->value) : 'unknown';
            $payment->status_label = $enumStatus ? $enumStatus->label : 'Unknown Status';
            $payment->status_color = $enumStatus ? $enumStatus->color_class : 'bg-gray-100 text-gray-800';
        }

        $latestOrder = Order::where('user_id', $user->id)->latest()->first();

        if ($latestOrder) {
            $subtotal = $latestOrder->total_price ?? 0;
            $shippingCost = $latestOrder->shipping_cost ?? 0;
            $totalDiscount = 0; // implementasi promo diskon bisa ditambahkan
            $tax = round(($subtotal - $totalDiscount) * 0.11);
            $grandTotal = ($subtotal - $totalDiscount) + $shippingCost + $tax;
        } else {
            $subtotal = $totalDiscount = $shippingCost = $tax = $grandTotal = 0;
        }

        $paymentStats = [
            'total_payments' => $payments->total(),
            'completed_payments' => $payments->where('status', 'completed')->count(),
            'pending_payments' => $payments->where('status', 'pending')->count(),
            'total_amount' => $payments->sum('total'),
        ];

        return view('payment.index', compact(
            'payments',
            'subtotal',
            'totalDiscount',
            'shippingCost',
            'tax',
            'grandTotal',
            'paymentStats'
        ));
    }

    /**
     * Store a new payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_method' => 'required|string',
                'total' => 'required|numeric|min:0',
            ]);

            $order = Order::findOrFail($validatedData['order_id']);

            // Check if the order belongs to the current user
            if ($order->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
            }

            // Get the pending status ID
            $pendingStatus = EnumPaymentStatus::where('value', 'PENDING')->first();
            if (!$pendingStatus) {
                throw new \Exception('Payment status "PENDING" not found in database');
            }

            $payment = new Payment();
            $payment->order_id = $validatedData['order_id'];
            $payment->payment_method = $validatedData['payment_method'];
            $payment->total = $validatedData['total'];
            $payment->enum_payment_status_id = $pendingStatus->id; // Using the ID instead of direct enum
            $payment->payment_date = now();
            $payment->save();

            // Redirect based on payment method
            switch ($validatedData['payment_method']) {
                case 'bank_transfer':
                    return redirect()->route('payment.bank-transfer', $payment->id);
                case 'qris':
                    return redirect()->route('payment.qris', $payment->id);
                case 'ewallet':
                    return redirect()->route('payment.ewallet', $payment->id);
                default:
                    return redirect()->route('payment.show', $payment->id);
            }
        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // Get the status information
        $enumStatus = EnumPaymentStatus::find($payment->enum_payment_status_id);
        $payment->status_label = $enumStatus ? $enumStatus->label : 'Unknown Status';
        $payment->status_color = $enumStatus ? $enumStatus->color_class : 'bg-gray-100 text-gray-800';

        return view('payment.show', compact('payment'));
    }

    /**
     * Display payment success page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function success($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Display payment cancel page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function cancel($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // Update payment status to cancelled
        $cancelledStatus = EnumPaymentStatus::where('value', 'CANCELLED')->first();
        if ($cancelledStatus) {
            $payment->enum_payment_status_id = $cancelledStatus->id;
            $payment->save();
        }

        return view('payment.cancel', compact('payment'));
    }

    /**
     * Display payment pending page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function pending($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        return view('payment.pending', compact('payment'));
    }

    /**
     * Display payment verification page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function verify($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        return view('payment.verify', compact('payment'));
    }

    /**
     * Process payment verification
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function verifyPost(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        try {
            $validatedData = $request->validate([
                'proof_file' => 'required|file|image|max:2048',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($request->hasFile('proof_file')) {
                $file = $request->file('proof_file');
                $path = $file->store('payment_proofs', 'public');
                $payment->proof_file = $path;
            }

            $payment->notes = $validatedData['notes'] ?? null;

            // Get the processing status
            $processingStatus = EnumPaymentStatus::where('value', 'PROCESSING')->first();
            if ($processingStatus) {
                $payment->enum_payment_status_id = $processingStatus->id;
            }

            $payment->verified_at = now();
            $payment->save();

            return redirect()->route('payment.success', $payment->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Pembayaran Anda sedang diproses.');
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'payment_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Process payment callback (from payment gateway)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function callback(Request $request)
    {
        try {
            // Get payment reference from request
            $reference = $request->input('reference');
            $status = $request->input('status');

            if (!$reference) {
                throw new \Exception('Payment reference not found');
            }

            // Find payment by reference
            $payment = Payment::where('reference', $reference)->firstOrFail();

            // Update payment status based on callback
            $statusMap = [
                'success' => 'COMPLETED',
                'completed' => 'COMPLETED',
                'settled' => 'COMPLETED',
                'pending' => 'PENDING',
                'waiting' => 'PENDING',
                'failed' => 'FAILED',
                'error' => 'FAILED',
                'cancelled' => 'CANCELLED',
                'canceled' => 'CANCELLED',
                'refunded' => 'REFUNDED',
            ];

            $statusValue = $statusMap[strtolower($status)] ?? 'PENDING';
            $statusRecord = EnumPaymentStatus::where('value', $statusValue)->first();

            if ($statusRecord) {
                $payment->enum_payment_status_id = $statusRecord->id;
            }

            $payment->callback_data = $request->all();
            $payment->updated_at = now();
            $payment->save();

            // Log the callback
            Log::info('Payment callback received', [
                'payment_id' => $payment->id,
                'reference' => $reference,
                'status' => $status,
                'data' => $request->all(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display QRIS payment method page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function qrisPayment($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // Generate QRIS code (mock implementation)
        $qrisCode = 'https://via.placeholder.com/300x300.png?text=QRIS+Code';

        return view('payment.qris', compact('payment', 'qrisCode'));
    }

    /**
     * Display bank transfer payment method page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function bankTransfer($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // Bank account information
        $banks = [
            [
                'name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Azka Garden',
            ],
            [
                'name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT Azka Garden',
            ]
        ];

        return view('payment.bank-transfer', compact('payment', 'banks'));
    }

    /**
     * Display e-wallet payment method page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function ewalletPayment($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        // Check if payment belongs to current user
        if ($payment->order->user_id !== Auth::id()) {
            return redirect()->route('payment.index')->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // E-wallet options
        $ewallets = [
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
        ];

        return view('payment.ewallet', compact('payment', 'ewallets'));
    }

    /**
     * Display payment history
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $user = Auth::user();

        $payments = Payment::with('order')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform the payment status
        foreach ($payments as $payment) {
            $enumStatus = EnumPaymentStatus::find($payment->enum_payment_status_id);
            $payment->status = $enumStatus ? strtolower($enumStatus->value) : 'unknown';
            $payment->status_label = $enumStatus ? $enumStatus->label : 'Unknown Status';
            $payment->status_color = $enumStatus ? $enumStatus->color_class : 'bg-gray-100 text-gray-800';
        }

        return view('payment.history', compact('payments'));
    }

    /**
     * Update payment status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $paymentId)
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            // Get status from request
            $statusValue = $request->input('status', 'pending');

            // Find status in database
            $status = EnumPaymentStatus::where('value', strtoupper($statusValue))->first();

            if (!$status) {
                throw new \Exception('Invalid payment status: ' . $statusValue);
            }

            // Set status ID instead of enum value
            $payment->enum_payment_status_id = $status->id;
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui',
                'status' => strtolower($status->value),
                'label' => $status->label,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating payment status', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'timestamp' => now(),
                'user' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
