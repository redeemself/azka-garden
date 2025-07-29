<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;

class ValidateOrderOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $orderId = $request->route('order');
        $user = auth()->user();
        
        if (!$orderId || !$user) {
            return redirect()->route('user.cart.index')
                ->withErrors('Pesanan tidak ditemukan atau sesi telah berakhir.');
        }
        
        $order = Order::find($orderId);
        
        if (!$order || $order->user_id !== $user->id) {
            return redirect()->route('user.orders.index')
                ->withErrors('Anda tidak memiliki akses ke pesanan ini.');
        }
        
        return $next($request);
    }
}