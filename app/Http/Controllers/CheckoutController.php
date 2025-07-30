<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Address;
use App\Models\Promotion;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has items in cart
        $cartItems = Cart::with(['product', 'product.images'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->count() == 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Check if user has shipping address
        $hasAddress = $user->addresses()->count() > 0;
        if (!$hasAddress) {
            return redirect()->route('user.addresses.create')
                ->with('error', 'Anda perlu menambahkan alamat pengiriman terlebih dahulu.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok tidak mencukupi untuk produk: {$item->product->name}. Stok tersedia: {$item->product->stock}");
            }
        }

        return view('checkout', compact('cartItems'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'shipping_address_id' => 'required|exists:addresses,id',
                'shipping_method' => 'required|string|max:50',
                'payment_method' => 'required|string|exists:payment_methods,code',
                'note' => 'nullable|string|max:500'
            ]);

            $user = Auth::user();
            
            // Verify cart is not empty
            $cartItems = Cart::with(['product'])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->count() == 0) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang Anda kosong.');
            }

            // Verify address belongs to user
            $address = Address::where('id', $validated['shipping_address_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$address) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Alamat pengiriman tidak valid.');
            }

            // Verify payment method exists and is active
            $paymentMethod = PaymentMethod::where('code', $validated['payment_method'])
                ->where('status', 1)
                ->first();

            if (!$paymentMethod) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Metode pembayaran tidak valid.');
            }

            // Start database transaction
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $totalDiscount = 0;
            $totalWeight = 0;
            $orderDetails = [];

            foreach ($cartItems as $item) {
                // Double-check stock availability
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if (!$product || $product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "Stok tidak mencukupi untuk produk: {$item->product->name}");
                }

                // Calculate pricing
                $promo = $item->promo_code ?? session('promo_code');
                $promotion = $promo ? Promotion::where('promo_code', $promo)->first() : null;
                $discount = 0;
                $unitPrice = $product->price;

                if ($promotion) {
                    if ($promotion->discount_type === 'percent') {
                        $percent = $promotion->discount_value ?: 10;
                        $discount = round($unitPrice * ($percent / 100));
                    } elseif ($promotion->discount_type === 'fixed') {
                        $discount = min($promotion->discount_value ?: 0, $unitPrice);
                    }
                }

                $discountedPrice = max(0, $unitPrice - $discount);
                $itemTotal = $discountedPrice * $item->quantity;
                
                $subtotal += $unitPrice * $item->quantity;
                $totalDiscount += $discount * $item->quantity;
                $totalWeight += $product->weight * $item->quantity;

                // Prepare order detail
                $orderDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $discountedPrice,
                    'subtotal' => $itemTotal,
                    'note' => null,
                    'interface_id' => 1
                ];

                // Update stock
                $product->decrement('stock', $item->quantity);
            }

            // Calculate shipping cost
            $shippingCost = $this->calculateShippingCost(
                $validated['shipping_method'], 
                $address, 
                $totalWeight
            );

            // Calculate final total
            $finalTotal = $subtotal - $totalDiscount + $shippingCost;

            // Generate unique order code
            $orderCode = $this->generateOrderCode();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'order_date' => now(),
                'enum_order_status_id' => 1, // WAITING_PAYMENT
                'total_price' => $finalTotal,
                'shipping_cost' => $shippingCost,
                'note' => $validated['note'],
                'payment_method' => $validated['payment_method'],
                'interface_id' => 1
            ]);

            // Create order details
            foreach ($orderDetails as $detail) {
                $detail['order_id'] = $order->id;
                OrderDetail::create($detail);
            }

            // Create shipping record
            $this->createShippingRecord($order, $validated['shipping_method'], $shippingCost);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            // Clear promo session
            session()->forget(['promo_code', 'shipping_method', 'payment_method']);

            // Commit transaction
            DB::commit();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_code' => $orderCode,
                'user_id' => $user->id,
                'total' => $finalTotal
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout process failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Calculate shipping cost based on method and address
     */
    private function calculateShippingCost($method, $address, $weight)
    {
        switch ($method) {
            case 'KURIR_TOKO':
                // This would ideally use Google Maps API to calculate distance
                // For now, return default rate
                return 15000;
                
            case 'GOSEND':
                return 25000;
                
            case 'JNE':
                return max(12000, ceil($weight) * 12000);
                
            case 'JNT':
                return max(14000, ceil($weight) * 14000);
                
            case 'SICEPAT':
                return max(15000, ceil($weight) * 15000);
                
            case 'AMBIL_SENDIRI':
                return 0;
                
            default:
                return 15000;
        }
    }

    /**
     * Generate unique order code
     */
    private function generateOrderCode()
    {
        do {
            $code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Create shipping record
     */
    private function createShippingRecord($order, $method, $cost)
    {
        $courierMap = [
            'KURIR_TOKO' => ['courier' => 'KURIR TOKO', 'service' => 'Internal'],
            'GOSEND' => ['courier' => 'GOSEND', 'service' => 'Sameday'],
            'JNE' => ['courier' => 'JNE', 'service' => 'REG'],
            'JNT' => ['courier' => 'J&T', 'service' => 'EZ'],
            'SICEPAT' => ['courier' => 'SICEPAT', 'service' => 'BEST'],
            'AMBIL_SENDIRI' => ['courier' => 'AMBIL_SENDIRI', 'service' => '-']
        ];

        $courierInfo = $courierMap[$method] ?? ['courier' => 'UNKNOWN', 'service' => 'Unknown'];

        Shipping::create([
            'order_id' => $order->id,
            'courier' => $courierInfo['courier'],
            'service' => $courierInfo['service'],
            'tracking_number' => null,
            'shipping_cost' => $cost,
            'status' => $method === 'AMBIL_SENDIRI' ? 'READY_FOR_PICKUP' : 'WAITING_PICKUP',
            'estimated_delivery' => null,
            'interface_id' => 1
        ]);
    }
}