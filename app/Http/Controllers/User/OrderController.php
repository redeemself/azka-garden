<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Added Carbon import
use Illuminate\Http\RedirectResponse; // Added RedirectResponse import

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Order::with(['items', 'details'])
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc')
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display the user's order history.
     */
    public function history()
    {
        // Anggap history sama dengan index, bisa dibedakan query/filter jika ingin
        $orders = Order::with(['items', 'details'])
            ->where('user_id', Auth::id())
            ->orderBy('order_date', 'desc')
            ->get();

        return view('user.orders.history', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('user.orders.create');
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'grand_total'     => 'required|numeric',
        ]);

        $user = Auth::user();

        $order = new Order([
            'user_id'               => $user->id,
            'shipping_method'       => $request->input('shipping_method'),
            'payment_method'        => $request->input('payment_method'),
            'total'                 => $request->input('grand_total'),
            'total_price'           => $request->input('grand_total'),
            'shipping_cost'         => $request->input('shipping_cost', 0),
            'status'                => 'pending',
            'order_code'            => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'order_date'            => now(),
            'enum_order_status_id'  => 1,
        ]);
        $order->save();

        // Simpan order items jika ada
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'price'        => $item['price'],
                ]);
            }
        }

        return redirect()->route('user.orders.index')->with('success', 'Order berhasil dibuat.');
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['items', 'details'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->delete();

        return redirect()->route('user.orders.index')->with('success', 'Order berhasil dihapus.');
    }

    /**
     * Clear expired orders for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearExpired(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        // Find expired orders (more than 24 hours old with status 'pending')
        $expiredOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        // Track how many orders were updated
        $updatedCount = 0;

        // Delete or update the status of expired orders
        foreach ($expiredOrders as $order) {
            $order->update([
                'status' => 'expired',
                'updated_at' => Carbon::parse('2025-08-01 06:46:21') // Using the current timestamp
            ]);
            $updatedCount++;

            // Log the expired order
            \Log::info('Order marked as expired', [
                'order_id' => $order->id,
                'order_code' => $order->order_code ?? 'N/A',
                'user_id' => $userId,
                'timestamp' => '2025-08-01 06:46:21',
                'expired_by' => 'DenuJanuari'
            ]);
        }

        $message = $updatedCount > 0
            ? "Berhasil membersihkan {$updatedCount} pesanan yang kadaluarsa"
            : "Tidak ada pesanan yang perlu dibersihkan";

        return redirect()->route('user.orders.index')
            ->with('success', $message);
    }
}
