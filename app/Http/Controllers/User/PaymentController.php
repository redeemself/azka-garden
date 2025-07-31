<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promotion;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display payment page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product', 'product.images'])->where('user_id', $user->id)->get();

        if ($cartItems->count() == 0) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang kosong!');
        }

        // Get checkout data from session or request
        $checkoutData = [
            'shipping_method' => $request->input('shipping_method') ?? session('shipping_method'),
            'payment_method' => $request->input('payment_method') ?? session('payment_method'),
            'shipping_address_id' => $request->input('shipping_address_id') ?? session('shipping_address_id'),
            'shipping_fee' => $request->input('shipping_fee') ?? session('shipping_fee', 0),
            'note' => $request->input('note') ?? session('note'),
            'distance_km' => $request->input('distance_km') ?? session('distance_km', 0),
        ];

        // Store checkout data in session
        session($checkoutData);

        // Get payment methods
        $localMethods = DB::table('local_payment_methods')->where('status', 1)->get();
        $globalMethods = DB::table('global_payment_methods')->where('status', 1)->get();
        $allMethods = collect($localMethods)->merge($globalMethods);

        // Get shipping address
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        if ($hasAddress && $checkoutData['shipping_address_id']) {
            $shipping_address = Address::find($checkoutData['shipping_address_id']);
        } else {
            $shipping_address = $hasAddress ? $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first() : null;
        }

        // Add shipping address to checkout data
        $checkoutData['shipping_address'] = $shipping_address;

        // Get promo info from session
        $promo_code = session('promo_code') ?? '';
        $promo_type = session('promo_type') ?? '';
        $promo_discount = session('promo_discount') ?? 0;

        // Calculate raw subtotal
        $raw_subtotal = 0;
        $total_weight = 0;

        foreach ($cartItems as $item) {
            $unit_price = $item->product->price ?? 0;
            $qty = $item->quantity ?? 0;
            $raw_subtotal += $unit_price * $qty;
            $total_weight += ($item->product->weight ?? 0) * $qty;
        }

        // Calculate discount
        $total_discount = $promo_type === 'percent' ? $raw_subtotal * ($promo_discount / 100) : $promo_discount;

        // Calculate subtotal after discount
        $subtotal_after_discount = max(0, $raw_subtotal - $total_discount);

        // Pajak 10% - calculate on post-discount subtotal
        $tax_rate = 0.1;
        $tax_total = round($subtotal_after_discount * $tax_rate);

        // Get shipping cost from checkout data or calculate
        $shipping_cost = $checkoutData['shipping_fee']
            ? $checkoutData['shipping_fee']
            : $this->calculateShippingCost($checkoutData['shipping_method'], $total_weight);

        $final_total = $subtotal_after_discount + $tax_total + $shipping_cost;

        // Current date time and user
        $currentDateTime = date('Y-m-d H:i:s');
        $currentUser = $user->username ?? $user->name ?? $user->email ?? 'Guest';

        return view('user.payment.index', compact(
            'cartItems',
            'checkoutData',
            'allMethods',
            'shipping_address',
            'raw_subtotal',
            'total_discount',
            'subtotal_after_discount',
            'tax_total',
            'shipping_cost',
            'final_total',
            'total_weight',
            'currentDateTime',
            'currentUser',
            'promo_code',
            'promo_type',
            'promo_discount'
        ));
    }

    /**
     * Process payment
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'final_amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $cartItems = Cart::with(['product'])->where('user_id', $user->id)->get();

        if ($cartItems->count() == 0) {
            return redirect()->route('user.cart.index')->with('error', 'Keranjang kosong!');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $this->generateOrderCode(),
                'order_date' => now(),
                'enum_order_status_id' => 1, // Pending
                'status' => 'PENDING',
                'total_price' => $request->final_amount,
                'shipping_method' => session('shipping_method', 'KURIR_TOKO'),
                'shipping_cost' => $request->shipping_fee ?? session('shipping_fee', 0),
                'payment_method' => $request->payment_method,
                'shipping_address_id' => session('shipping_address_id'),
                'note' => session('note'),
                'discount' => $request->discount_amount ?? session('discount_amount', 0),
                'promo_code' => session('promo_code'),
                'promo_type' => session('promo_type'),
                'promo_discount' => session('promo_discount', 0),
                'tax_amount' => $request->tax_amount ?? session('tax_amount', 0),
                'interface_id' => 1,
                'created_by' => $user->username ?? $user->name ?? $user->email,
            ]);

            // Create order details
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            // Clear checkout session data
            session()->forget([
                'shipping_method',
                'payment_method',
                'shipping_address_id',
                'shipping_fee',
                'note',
                'discount_amount',
                'promo_code',
                'promo_type',
                'promo_discount',
                'tax_amount',
                'distance_km'
            ]);

            DB::commit();

            return redirect()->route('user.orders.show', $order->id)->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Calculate shipping cost
     */
    private function calculateShippingCost($method, $weight)
    {
        switch ($method) {
            case 'AMBIL_SENDIRI':
                return 0;
            case 'KURIR_TOKO':
                return 15000; // Default, bisa dihitung berdasarkan jarak
            case 'GOSEND':
                return 25000;
            case 'JNE':
                return 12000 + (ceil($weight) * 5000);
            case 'JNT':
                return 14000 + (ceil($weight) * 6000);
            case 'SICEPAT':
                return 15000 + (ceil($weight) * 5500);
            default:
                return 10000;
        }
    }

    /**
     * Generate unique order code
     */
    private function generateOrderCode()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        return "AZK{$date}{$random}";
    }
}
