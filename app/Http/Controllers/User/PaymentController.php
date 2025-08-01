<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class PaymentController extends Controller
{
    public function index()
    {
        // Tampilkan halaman form pembayaran, jika ada.
        return view('user.payment.index');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $order = new Order([
            'user_id'               => $user->id,
            'shipping_method'       => $request->input('shipping_method'),
            'payment_method'        => $request->input('payment_method'),
            'total'                 => $request->input('grand_total'),
            'total_price'           => $request->input('grand_total'),
            'shipping_cost'         => $request->input('shipping_cost', 0), // <-- TAMBAHKAN INI!
            'status'                => 'pending',
            'order_code'            => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'order_date'            => now(),
            'enum_order_status_id'  => 1,
        ]);
        $order->save();

        // Simpan items
        foreach ($request->input('cart_items', []) as $item) {
            $order->items()->create([
                'product_id'   => $item['product_id'] ?? null,
                'product_name' => $item['product_name'] ?? null,
                'quantity'     => $item['quantity'] ?? 0,
                'price'        => $item['price'] ?? 0,
            ]);
        }

        // (Opsional) Bersihkan keranjang user
        // contoh: Cart::where('user_id', $user->id)->delete();

        // Redirect ke daftar pesanan
        return redirect()->route('user.orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
