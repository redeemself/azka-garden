<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PaymentMethod;
use Carbon\Carbon;

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
    public function history()
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
    public function show($order)
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
            return view('user.orders.confirm', compact('cartItems'));
        }
    }

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
    public function pay($order)
    {
        $order = Order::where('id', $order)
            ->where('user_id', Auth::id())
            ->with(['details.product', 'shipping', 'payment.method'])
            ->firstOrFail();

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
            if ($paymentMethod) {
                $order->payments()->create([
                    'method_id' => $paymentMethod->id,
                    'transaction_code' => strtoupper(uniqid('TRX')),
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
    public function complete($orderId)
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

        $expiredId = DB::table('enum_order_status')->where('value', 'EXPIRED')->value('id');
        if (!$expiredId) {
            return back()->withErrors('Status EXPIRED tidak ditemukan di enum_order_status. Silakan cek database.');
        }
        // Update status order
        $order->enum_order_status_id = $expiredId;
        $order->save();

        // Update status payment (opsional, best practice)
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

        // Hapus orders pada tabel 'orders' yang status dibatalkan/expired milik user
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