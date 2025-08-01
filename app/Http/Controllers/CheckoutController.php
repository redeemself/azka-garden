<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail; // Ganti OrderItem dengan OrderDetail
use App\Models\Address;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     */
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
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Check if user has shipping address
        $addresses = $user->addresses()->get();
        $hasAddress = $addresses->count() > 0;
        $primaryAddress = $addresses->where('is_primary', 1)->first() ?? $addresses->first();

        if (!$hasAddress) {
            return redirect()->route('user.address.create')
                ->with('error', 'Anda perlu menambahkan alamat pengiriman terlebih dahulu.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('user.cart.index')
                    ->with('error', "Stok tidak mencukupi untuk produk: {$item->product->name}. Stok tersedia: {$item->product->stock}");
            }
        }

        // Get available payment methods
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        // Calculate totals
        $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $discount = session('promo_type') === 'percent'
            ? $subtotal * (session('promo_discount', 0) / 100)
            : session('promo_discount', 0);

        $subtotalAfterDiscount = max(0, $subtotal - $discount);
        $tax = $subtotalAfterDiscount * 0.1; // 10% tax

        // Store location for distance calculation
        $storeLocation = [
            'lat' => -6.4122794,
            'lng' => 106.829692,
            'address' => 'Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412'
        ];

        return view('checkout', compact(
            'cartItems',
            'addresses',
            'primaryAddress',
            'hasAddress',
            'paymentMethods',
            'subtotal',
            'discount',
            'subtotalAfterDiscount',
            'tax',
            'storeLocation'
        ));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        try {
            // Enhanced validation untuk menerima data dari cart
            $validated = $request->validate([
                'shipping_address_id' => 'required|exists:addresses,id',
                'shipping_method_id' => 'required|string|max:50',
                'payment_method' => 'required|string',
                'shipping_fee' => 'required|numeric|min:0',
                'distance_km' => 'nullable|numeric|min:0',
                'customer_lat' => 'nullable|numeric',
                'customer_lng' => 'nullable|numeric',
                'note' => 'nullable|string|max:500'
            ]);

            $user = Auth::user();

            // Verify cart is not empty
            $cartItems = Cart::with(['product'])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->count() == 0) {
                return redirect()->route('user.cart.index')
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

            // Start database transaction
            DB::beginTransaction();

            try {
                // Calculate order totals
                $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
                $discount = session('promo_type') === 'percent'
                    ? $subtotal * (session('promo_discount', 0) / 100)
                    : session('promo_discount', 0);

                $subtotalAfterDiscount = max(0, $subtotal - $discount);
                $tax = $subtotalAfterDiscount * 0.1;
                $shippingFee = $validated['shipping_fee'];
                $grandTotal = $subtotalAfterDiscount + $tax + $shippingFee;

                // Generate order code yang unik
                $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

                // Create order sesuai dengan struktur yang ada
                $order = Order::create([
                    'order_code' => $orderCode,
                    'user_id' => $user->id,
                    'enum_order_status_id' => 1, // Status pending (sesuai enum_order_status)
                    'total_price' => $grandTotal,
                    'shipping_cost' => $shippingFee,
                    'payment_method' => $validated['payment_method'],
                    'note' => $validated['note'] ?? null,
                    'order_date' => now(),
                    'interface_id' => 8 // Default interface ID untuk sistem
                ]);

                // Create order details menggunakan OrderDetail
                foreach ($cartItems as $cartItem) {
                    // Check stock again
                    if ($cartItem->product->stock < $cartItem->quantity) {
                        throw new \Exception("Stok tidak mencukupi untuk produk: {$cartItem->product->name}");
                    }

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'subtotal' => $cartItem->price * $cartItem->quantity,
                        'note' => "Shipping: {$validated['shipping_method_id']}, Distance: {$validated['distance_km']}km",
                        'interface_id' => 8
                    ]);

                    // Update product stock
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                // Clear cart
                Cart::where('user_id', $user->id)->delete();

                // Clear promo session
                session()->forget(['promo_code', 'promo_discount', 'promo_type']);

                // Store shipping data in session for later use
                session([
                    'order_shipping_data' => [
                        'order_id' => $order->id,
                        'shipping_method' => $validated['shipping_method_id'],
                        'shipping_address' => [
                            'recipient' => $address->recipient,
                            'phone' => $address->phone_number,
                            'address' => $address->full_address,
                            'city' => $address->city,
                            'zip_code' => $address->zip_code,
                            'latitude' => $validated['customer_lat'],
                            'longitude' => $validated['customer_lng'],
                            'distance_km' => $validated['distance_km']
                        ]
                    ]
                ]);

                // Commit transaction
                DB::commit();

                Log::info('Order created successfully', [
                    'order_id' => $order->id,
                    'order_code' => $order->order_code,
                    'user_id' => $user->id,
                    'total_amount' => $grandTotal,
                    'shipping_method' => $validated['shipping_method_id'],
                    'distance_km' => $validated['distance_km'],
                    'timestamp' => '2025-07-31 15:46:39',
                    'user' => 'DenuJanuari'
                ]);

                return redirect()->route('user.orders.show', $order->id)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error creating order: ' . $e->getMessage(), [
                    'timestamp' => '2025-07-31 15:46:39',
                    'user' => 'DenuJanuari'
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Checkout validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'timestamp' => '2025-07-31 15:46:39',
                'user' => 'DenuJanuari'
            ]);

            return redirect()->route('checkout.index')
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}
