<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promotion;
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
            'note' => $request->input('note') ?? session('note'),
        ];

        // Store checkout data in session
        session($checkoutData);

        // Get payment methods
        $localMethods = DB::table('local_payment_methods')->where('status', 1)->get();
        $globalMethods = DB::table('global_payment_methods')->where('status', 1)->get();
        $allMethods = collect($localMethods)->merge($globalMethods);

        // Get shipping address
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        $primaryAddress = $hasAddress ? $user->addresses()->where('is_primary', 1)->first() ?? $user->addresses()->first() : null;

        // Calculate totals
        $promo_code = session('promo_code') ?? '';
        $tax_rate = 0.10;
        $grand_total = 0;
        $total_discount = 0;
        $total_weight = 0;
        
        foreach($cartItems as $item) {
            $promo = $item->promo_code ?? $promo_code;
            $promotion = $promo ? Promotion::where('promo_code', $promo)->first() : null;
            $discount = 0;
            $unit_price = $item->product->price ?? 0;
            $qty = $item->quantity ?? 0;

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {
                    $percent = $promotion->discount_value ?: 10;
                    $discount = round($unit_price * ($percent / 100));
                } elseif ($promotion->discount_type === 'fixed') {
                    $discount = min($promotion->discount_value ?: 0, $unit_price);
                }
            }

            $discounted_price = max(0, $unit_price - $discount);
            $item_total = $discounted_price * $qty;
            $grand_total += $item_total;
            $total_discount += $discount * $qty;
            $total_weight += ($item->product->weight ?? 0) * $qty;
        }
        
        $tax_total = round($grand_total * $tax_rate);
        
        // Calculate shipping cost (simplified)
        $shipping_cost = $this->calculateShippingCost($checkoutData['shipping_method'], $total_weight);
        
        $final_total = $grand_total + $tax_total + $shipping_cost;

        return view('user.payment.index', compact(
            'cartItems',
            'checkoutData', 
            'allMethods',
            'primaryAddress',
            'grand_total',
            'total_discount',
            'tax_total',
            'shipping_cost',
            'final_total',
            'total_weight'
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
                'total_price' => $request->final_amount,
                'shipping_cost' => session('shipping_cost', 0),
                'note' => session('note'),
                'payment_method' => $request->payment_method,
                'interface_id' => 1,
            ]);

            // Create order details
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'total_price' => $item->product->price * $item->quantity,
                ]);
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            // Clear checkout session data
            session()->forget(['shipping_method', 'payment_method', 'shipping_address_id', 'note', 'shipping_cost']);

            DB::commit();

            return redirect()->route('user.orders.show', $order->id)->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
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
                return 12000;
            case 'JNT':
                return 14000;
            case 'SICEPAT':
                return 15000;
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