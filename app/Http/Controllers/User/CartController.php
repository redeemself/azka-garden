<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\Address;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function index()
    {
        $items = Auth::check()
            ? Cart::with('product')->where('user_id', Auth::id())->get()
            : collect(Session::get('cart_items', []));

        $shipQ = ShippingMethod::query();

        if (Schema::hasColumn('shipping_methods', 'is_active')) {
            $shipQ->where('is_active', 1);
        }

        if (
            Schema::hasColumn('shipping_methods', 'start_date') &&
            Schema::hasColumn('shipping_methods', 'end_date')
        ) {
            $today = now()->toDateString();

            $shipQ->where(function ($q) use ($today) {
                $q->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $today);
            })->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            });
        }

        if (Schema::hasColumn('shipping_methods', 'sort')) {
            $shipQ->orderBy('sort');
        } else {
            $shipQ->orderBy('id');
        }

        $shippingMethods = $shipQ->get();
        $selectedShipId  = Session::get('shipping_method_id');

        return view('user.cart', compact('items', 'shippingMethods', 'selectedShipId'));
    }

    /**
     * Direct checkout from cart (redirect to checkout page)
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function checkout(Request $request): RedirectResponse
    {
        try {
            // Log checkout attempt
            Log::info('Direct cart checkout initiated', [
                'user_id'      => auth()->id(),
                'request_data' => $request->all(),
                'timestamp'    => '2025-07-31 16:35:21',
                'user'         => 'DenuJanuari'
            ]);

            $user = Auth::user();

            if (!$user) {
                Log::warning('Unauthenticated checkout attempt', [
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp'  => '2025-07-31 16:35:21'
                ]);

                return redirect()->route('login')
                    ->with('error', 'Anda harus login untuk melakukan checkout');
            }

            // Periksa apakah ada item di keranjang
            $cartItems = Cart::with(['product', 'product.images'])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                Log::warning('Empty cart checkout attempt', [
                    'user_id'   => $user->id,
                    'timestamp' => '2025-07-31 16:35:21'
                ]);

                return redirect()->route('user.cart.index')
                    ->with('error', 'Keranjang Anda kosong. Silahkan tambahkan produk terlebih dahulu.');
            }

            // Validasi stock sebelum checkout
            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    Log::warning('Insufficient stock for checkout', [
                        'user_id'         => $user->id,
                        'product_id'      => $item->product_id,
                        'product_name'    => $item->product->name,
                        'requested_qty'   => $item->quantity,
                        'available_stock' => $item->product->stock,
                        'timestamp'       => '2025-07-31 16:35:21'
                    ]);

                    return redirect()->route('user.cart.index')
                        ->with('error', "Stok tidak mencukupi untuk produk {$item->product->name}. Stok tersedia: {$item->product->stock}");
                }
            }

            // Ambil data dari request dengan default values yang sesuai dengan cart.blade.php
            $shippingMethodId = $request->input('shipping_method_id', 'KURIR_TOKO_SEDANG');
            $shippingFee      = floatval($request->input('shipping_fee', 15000));
            $distanceKm       = floatval($request->input('distance_km', 7.2));
            $customerLat      = $request->input('customer_lat');
            $customerLng      = $request->input('customer_lng');

            // Validasi metode pengiriman yang diizinkan
            $allowedShippingMethods = [
                'KURIR_TOKO_DEKAT',
                'KURIR_TOKO_SEDANG',
                'KURIR_TOKO_JAUH',
                'KURIR_TOKO',
                'GOSEND',
                'JNE',
                'JNT',
                'SICEPAT',
                'AMBIL_SENDIRI'
            ];

            if (!in_array($shippingMethodId, $allowedShippingMethods)) {
                Log::warning('Invalid shipping method for checkout', [
                    'shipping_method' => $shippingMethodId,
                    'user_id'         => $user->id,
                    'timestamp'       => '2025-07-31 16:35:21'
                ]);

                $shippingMethodId = 'KURIR_TOKO_SEDANG'; // Fallback to default
                $shippingFee      = 15000;
            }

            // Hitung total berat untuk shipping calculation
            $totalWeight = $cartItems->sum(function ($item) {
                return ($item->product->weight ?? 0) * $item->quantity;
            });

            // Periksa alamat pengiriman user
            $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count() > 0;
            $primaryAddress = null;
            if ($hasAddress) {
                $primaryAddress = $user->addresses()->where('is_primary', 1)->first()
                    ?? $user->addresses()->first();
            }

            // Jika tidak ada alamat dan bukan ambil sendiri, redirect ke create address
            if (!$primaryAddress && $shippingMethodId !== 'AMBIL_SENDIRI') {
                Log::warning('Missing shipping address for checkout', [
                    'user_id'         => $user->id,
                    'shipping_method' => $shippingMethodId,
                    'timestamp'       => '2025-07-31 16:35:21'
                ]);

                return redirect()->route('user.address.create')
                    ->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu untuk melanjutkan checkout.');
            }

            // Data yang akan disimpan ke session untuk checkout page
            $checkoutData = [
                'checkout_shipping_method'    => $shippingMethodId,
                'checkout_shipping_fee'       => $shippingFee,
                'checkout_distance_km'        => $distanceKm,
                'checkout_customer_lat'       => $customerLat ?: ($primaryAddress->latitude ?? null),
                'checkout_customer_lng'       => $customerLng ?: ($primaryAddress->longitude ?? null),
                'checkout_shipping_address_id' => $primaryAddress ? $primaryAddress->id : null,
                'checkout_total_weight'       => $totalWeight,
                'checkout_timestamp'          => '2025-07-31 16:35:21',
                'checkout_user'               => 'DenuJanuari',
            ];

            // Tambahkan data promo yang sudah ada (jika ada)
            $promoData = [
                'checkout_promo_code'     => Session::get('promo_code', ''),
                'checkout_promo_type'     => Session::get('promo_type', ''),
                'checkout_promo_discount' => Session::get('promo_discount', 0),
            ];

            // Gabungkan semua data checkout
            $allCheckoutData = array_merge($checkoutData, $promoData);

            // Simpan data ke session untuk checkout page
            Session::put($allCheckoutData);

            // Log successful checkout data preparation
            Log::info('Cart checkout data prepared successfully', [
                'user_id'         => $user->id,
                'cart_items_count' => $cartItems->count(),
                'shipping_method' => $shippingMethodId,
                'shipping_fee'    => $shippingFee,
                'distance_km'     => $distanceKm,
                'total_weight'    => $totalWeight,
                'has_address'     => $hasAddress,
                'timestamp'       => '2025-07-31 16:35:21',
                'user'            => 'DenuJanuari',
                'session_data_keys' => array_keys($allCheckoutData)
            ]);

            // UPDATED: Redirect ke checkout.blade.php
            return redirect()->route('user.checkout.index')
                ->with('success', 'Data pengiriman berhasil disimpan. Silakan lanjutkan ke halaman checkout.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Cart checkout error', [
                'user_id'      => Auth::id(),
                'error_message' => $e->getMessage(),
                'error_trace'  => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'timestamp'    => '2025-07-31 16:35:21',
                'user'         => 'DenuJanuari'
            ]);

            return redirect()->route('user.cart.index')
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi customer service.');
        }
    }

    /**
     * Hapus item dari keranjang (ENHANCED)
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function remove(Request $request, $id)
    {
        try {
            Log::info('Cart item removal initiated', [
                'item_id'   => $id,
                'user_id'   => auth()->id(),
                'timestamp' => '2025-07-31 16:35:21',
                'user'      => 'DenuJanuari'
            ]);

            if (Auth::check()) {
                // Untuk user yang login, hapus dari database
                $cartItem = Cart::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

                if (!$cartItem) {
                    Log::warning('Cart item not found for removal', [
                        'item_id' => $id,
                        'user_id' => Auth::id(),
                        'timestamp' => '2025-07-31 16:35:21'
                    ]);

                    return $this->respond(false, 'Item tidak ditemukan di keranjang', 404);
                }

                // Log item yang akan dihapus
                Log::info('Removing cart item', [
                    'item_id'      => $cartItem->id,
                    'product_id'   => $cartItem->product_id,
                    'product_name' => $cartItem->product->name ?? 'Unknown',
                    'quantity'     => $cartItem->quantity,
                    'price'        => $cartItem->price,
                    'user_id'      => $cartItem->user_id,
                    'timestamp'    => '2025-07-31 16:35:21'
                ]);

                $cartItem->delete();
            } else {
                // Untuk guest user, hapus dari session
                $items = collect(Session::get('cart_items', []));
                $originalCount = $items->count();

                $items = $items->reject(function ($item) use ($id) {
                    return $item['id'] == $id;
                })->values();

                if ($items->count() === $originalCount) {
                    Log::warning('Cart item not found in session for removal', [
                        'item_id' => $id,
                        'session_items_count' => $originalCount,
                        'timestamp' => '2025-07-31 16:35:21'
                    ]);

                    return $this->respond(false, 'Item tidak ditemukan di keranjang', 404);
                }

                Session::put('cart_items', $items->all());

                Log::info('Cart item removed from session', [
                    'item_id' => $id,
                    'items_before' => $originalCount,
                    'items_after' => $items->count(),
                    'timestamp' => '2025-07-31 16:35:21'
                ]);
            }

            // Hitung ulang jumlah item di keranjang
            $newCartCount = $this->cartCount();

            Log::info('Cart item removed successfully', [
                'item_id' => $id,
                'user_id' => auth()->id(),
                'new_cart_count' => $newCartCount,
                'timestamp' => '2025-07-31 16:35:21',
                'user' => 'DenuJanuari'
            ]);

            // Return response with updated cart count
            return $this->respond(true, 'Item berhasil dihapus dari keranjang', 200, null, [
                'cart_count' => $newCartCount,
                'removed_item_id' => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing cart item', [
                'item_id' => $id,
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'timestamp' => '2025-07-31 16:35:21',
                'user' => 'DenuJanuari'
            ]);

            return $this->respond(false, 'Terjadi kesalahan saat menghapus item. Silakan coba lagi.', 500, $e->getMessage());
        }
    }

    /**
     * Proceed to checkout (legacy method - kept for backward compatibility)
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function proceedToCheckout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $cartItems = Cart::with(['product', 'product.images'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Get selected shipping method
        $selectedShipId = Session::get('shipping_method_id');
        $shippingMethod = null;

        if ($selectedShipId) {
            $shippingMethod = ShippingMethod::find($selectedShipId);
        }

        // Default to KURIR_TOKO if no shipping method selected
        $shipping_method_code = $shippingMethod->code ?? 'KURIR_TOKO';

        // Calculate total weight
        $total_weight = 0;
        foreach ($cartItems as $item) {
            $total_weight += ($item->product->weight ?? 0) * $item->quantity;
        }

        // Get shipping fee from the shipping method
        $shipping_fee = $this->calculateShippingFee($shipping_method_code, $total_weight, 7.5);

        // Get default payment method (if available)
        $payment_method = $request->input('payment_method', null);

        // Check if user has an address
        $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
        $primaryAddress = null;

        if ($hasAddress) {
            $primaryAddress = $user->addresses()->where('is_primary', 1)->first()
                ?? $user->addresses()->first();
        }

        // Store checkout data in session with updated keys
        Session::put([
            'checkout_shipping_method' => $shipping_method_code,
            'checkout_payment_method'  => $payment_method,
            'checkout_shipping_address_id' => $primaryAddress ? $primaryAddress->id : null,
            'checkout_shipping_fee'    => $shipping_fee,
            'checkout_distance_km'     => 7.5,
            'checkout_customer_lat'    => $primaryAddress->latitude ?? null,
            'checkout_customer_lng'    => $primaryAddress->longitude ?? null,
            'checkout_total_weight'    => $total_weight,
        ]);

        // Get promo info from session
        $promo_code = session('promo_code') ?? '';
        $promo_type = session('promo_type') ?? '';
        $promo_discount = session('promo_discount') ?? 0;

        // Ensure these are also saved in session with checkout prefix
        Session::put([
            'checkout_promo_code'     => $promo_code,
            'checkout_promo_type'     => $promo_type,
            'checkout_promo_discount' => $promo_discount,
        ]);

        // Current date time and user for display - UPDATED
        $currentDateTime = '2025-07-31 16:35:21';
        $currentUser     = 'DenuJanuari';

        Session::put([
            'checkout_timestamp' => $currentDateTime,
            'checkout_user'      => $currentUser,
        ]);

        // UPDATED: Redirect ke checkout.blade.php
        return redirect()->route('user.checkout.index');
    }

    /**
     * Handle checkout process from cart page (legacy AJAX method)
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function processCheckout(Request $request)
    {
        try {
            Log::info('Cart checkout process request received', [
                'user_id'      => auth()->id(),
                'request_data' => $request->all(),
                'timestamp'    => '2025-07-31 16:35:21',
                'user'         => 'DenuJanuari'
            ]);

            $validator = Validator::make($request->all(), [
                'shipping_method_id' => 'required|string|max:50',
                'distance_km'        => 'nullable|numeric|min:0|max:1000',
                'shipping_fee'       => 'nullable|numeric|min:0|max:1000000',
                'customer_lat'       => 'nullable|numeric|between:-90,90',
                'customer_lng'       => 'nullable|numeric|between:-180,180',
            ], [
                'shipping_method_id.required' => 'Metode pengiriman harus dipilih',
                'distance_km.numeric'         => 'Jarak harus berupa angka',
                'shipping_fee.numeric'        => 'Biaya pengiriman harus berupa angka',
                'customer_lat.between'        => 'Latitude tidak valid',
                'customer_lng.between'        => 'Longitude tidak valid',
            ]);

            if ($validator->fails()) {
                Log::warning('Cart checkout validation failed', [
                    'errors'    => $validator->errors()->toArray(),
                    'user_id'   => auth()->id(),
                    'timestamp' => '2025-07-31 16:35:21'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'error'   => $validator->errors()->first(),
                        'data'    => []
                    ], 422);
                }

                return back()->withErrors($validator)->withInput()
                    ->with('error', 'Terjadi kesalahan: ' . $validator->errors()->first());
            }

            if (!Auth::check()) {
                Log::warning('Unauthenticated checkout attempt', [
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp'  => '2025-07-31 16:35:21'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan login terlebih dahulu',
                        'error'   => 'Unauthorized',
                        'data'    => []
                    ], 401);
                }

                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $user = Auth::user();
            $cartItems = Cart::with(['product', 'product.images'])->where('user_id', $user->id)->get();

            if ($cartItems->count() == 0) {
                Log::warning('Empty cart checkout attempt', [
                    'user_id'   => $user->id,
                    'timestamp' => '2025-07-31 16:35:21'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Keranjang kosong',
                        'error'   => 'Empty cart',
                        'data'    => []
                    ], 400);
                }

                return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
            }

            $shippingMethodId = $request->input('shipping_method_id');
            $distanceKm       = floatval($request->input('distance_km', 7.5));
            $shippingFee      = floatval($request->input('shipping_fee', 0));
            $customerLat      = $request->input('customer_lat');
            $customerLng      = $request->input('customer_lng');

            $allowedShippingMethods = [
                'KURIR_TOKO_DEKAT',
                'KURIR_TOKO_SEDANG',
                'KURIR_TOKO_JAUH',
                'KURIR_TOKO',
                'GOSEND',
                'JNE',
                'JNT',
                'SICEPAT',
                'AMBIL_SENDIRI'
            ];

            if (!in_array($shippingMethodId, $allowedShippingMethods)) {
                Log::warning('Invalid shipping method', [
                    'shipping_method' => $shippingMethodId,
                    'user_id'         => $user->id,
                    'timestamp'       => '2025-07-31 16:35:21'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Metode pengiriman tidak valid',
                        'error'   => 'Invalid shipping method',
                        'data'    => []
                    ], 400);
                }

                return back()->with('error', 'Metode pengiriman tidak valid')->withInput();
            }

            $totalWeight = $cartItems->sum(function ($item) {
                return ($item->product->weight ?? 0) * $item->quantity;
            });

            $calculatedShippingFee = $this->calculateShippingFee($shippingMethodId, $totalWeight, $distanceKm);

            $hasAddress = $user && method_exists($user, 'addresses') && $user->addresses()->count();
            $primaryAddress = null;
            if ($hasAddress) {
                $primaryAddress = $user->addresses()->where('is_primary', 1)->first()
                    ?? $user->addresses()->first();
            }

            if (!$primaryAddress && $shippingMethodId !== 'AMBIL_SENDIRI') {
                Log::warning('Missing shipping address', [
                    'user_id'         => $user->id,
                    'shipping_method' => $shippingMethodId,
                    'timestamp'       => '2025-07-31 16:35:21'
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Alamat pengiriman diperlukan',
                        'error'   => 'Address required',
                        'data'    => []
                    ], 400);
                }

                return redirect()->route('user.address.create')
                    ->with('error', 'Silakan tambahkan alamat pengiriman terlebih dahulu');
            }

            $checkoutData = [
                'checkout_shipping_method'    => $shippingMethodId,
                'checkout_distance_km'         => $distanceKm,
                'checkout_shipping_fee'        => $calculatedShippingFee,
                'checkout_customer_lat'        => $customerLat ?: ($primaryAddress->latitude ?? null),
                'checkout_customer_lng'        => $customerLng ?: ($primaryAddress->longitude ?? null),
                'checkout_shipping_address_id' => $primaryAddress ? $primaryAddress->id : null,
                'checkout_total_weight'        => $totalWeight,
                'checkout_timestamp'           => '2025-07-31 16:35:21',
                'checkout_user'                => 'DenuJanuari',
            ];

            $promoData = [
                'checkout_promo_code'     => Session::get('promo_code', ''),
                'checkout_promo_type'     => Session::get('promo_type', ''),
                'checkout_promo_discount' => Session::get('promo_discount', 0),
            ];

            $allCheckoutData = array_merge($checkoutData, $promoData);
            Session::put($allCheckoutData);

            Log::info('Cart checkout data saved to session', [
                'user_id'         => $user->id,
                'shipping_method' => $shippingMethodId,
                'distance_km'     => $distanceKm,
                'shipping_fee'    => $calculatedShippingFee,
                'timestamp'       => '2025-07-31 16:35:21',
                'user'            => 'DenuJanuari',
                'session_data_keys' => array_keys($allCheckoutData)
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data checkout berhasil disimpan',
                    'error'   => null,
                    'data'    => [
                        'redirect_url'    => route('user.checkout.index'),
                        'shipping_method' => $shippingMethodId,
                        'shipping_fee'    => $calculatedShippingFee,
                        'distance_km'     => $distanceKm
                    ]
                ], 200);
            }

            // UPDATED: Redirect ke checkout.blade.php
            return redirect()->route('user.checkout.index')
                ->with('success', 'Data pengiriman berhasil disimpan. Silakan lanjutkan checkout.');
        } catch (\Exception $e) {
            Log::error('Cart checkout error', [
                'user_id'      => Auth::id(),
                'error'        => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'timestamp'    => '2025-07-31 16:35:21'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
                    'data'    => []
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Calculate shipping fee based on method, weight and distance
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    private function calculateShippingFee(string $shippingMethod, float $totalWeight, float $distanceKm): int
    {
        switch ($shippingMethod) {
            case 'AMBIL_SENDIRI':
                return 0;
            case 'KURIR_TOKO':
            case 'KURIR_TOKO_DEKAT':
                return ($distanceKm <= 5) ? 10000 : (($distanceKm <= 10) ? 15000 : 20000);
            case 'KURIR_TOKO_SEDANG':
                return 15000;
            case 'KURIR_TOKO_JAUH':
                return 20000;
            case 'GOSEND':
                return max(15000, min(30000, $distanceKm * 2000));
            case 'JNE':
                return 12000 + (ceil($totalWeight) * 5000);
            case 'JNT':
                return 14000 + (ceil($totalWeight) * 6000);
            case 'SICEPAT':
                return 15000 + (ceil($totalWeight) * 5500);
            default:
                return 10000;
        }
    }

    /**
     * Tambah produk ke keranjang (AJAX/normal)
     * Updated: 2025-07-31 16:35:21 by DenuJanuari
     */
    public function add(Request $r): JsonResponse
    {
        $v = Validator::make($r->all(), [
            'product_id' => ['required', Rule::exists('products', 'id')],
            'quantity'   => ['sometimes', 'integer', 'min:1'],
            'price'      => ['sometimes', 'integer', 'min:0'],
        ]);

        if ($v->fails()) {
            return $this->respond(false, 'Validasi gagal', 422, $v->errors()->first());
        }

        $qty = max(1, (int) $r->input('quantity', 1));
        $codePrice = (int) $r->input('price', 0);

        $product = Product::select(['id', 'name', 'price', 'image_url'])
            ->findOrFail($r->product_id);

        $finalPrice = $codePrice > 0 ? $codePrice : $product->price;

        if (Auth::check()) {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $product->id],
                [
                    'price'    => $finalPrice,
                    'quantity' => \DB::raw("quantity + {$qty}"),
                    'name'     => $product->name,
                    'image'    => $product->image_url,
                ]
            );
        } else {
            $session = collect(Session::get('cart_items', []));
            $idx = $session->search(fn($it) => $it['product_id'] == $product->id);

            if ($idx !== false) {
                $session[$idx]['quantity'] += $qty;
            } else {
                $session->push([
                    'id'         => uniqid(),
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => $finalPrice,
                    'quantity'   => $qty,
                    'image'      => $product->image_url,
                ]);
            }
            Session::put('cart_items', $session->all());
        }

        return $this->respond(true, 'Produk ditambahkan', 200, null, [
            'cart_count' => $this->cartCount(),
        ]);
    }

    /**
     * Ubah kuantitas (PATCH)
     */
    public function update(Request $r, $id)
    {
        $qty = max(1, (int) $r->input('quantity', 1));

        if (Auth::check()) {
            $row = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $row->update(['quantity' => $qty]);
        } else {
            $items = collect(Session::get('cart_items', []));
            $idx = $items->search(fn($it) => $it['id'] == $id);
            if ($idx === false) {
                return $this->respond(false, 'Item tidak ditemukan', 404);
            }
            $items[$idx]['quantity'] = $qty;
            Session::put('cart_items', $items->all());
        }

        return $this->respond(true, 'Kuantitas berhasil diperbarui');
    }

    /** Alias POST untuk backward compatibility */
    public function updatePost(Request $r, $id)
    {
        return $this->update($r, $id);
    }

    /**
     * Aktifkan promo
     */
    public function applyPromo(Request $r)
    {
        $r->validate(['promo_code' => 'required|string|max:50']);
        $valid = [
            'JULI10'    => ['type' => 'percent', 'discount' => 10],
            'HEMAT5'    => ['type' => 'fixed', 'discount' => 5000],
            'AGUSTUS15' => ['type' => 'percent', 'discount' => 15],
            'DISKON10K' => ['type' => 'fixed', 'discount' => 10000],
        ];
        $code = trim($r->promo_code);
        if (!isset($valid[$code])) {
            return $this->respond(false, 'Kode promo tidak valid', 422);
        }
        Session::put([
            'promo_code'     => $code,
            'promo_type'     => $valid[$code]['type'],
            'promo_discount' => $valid[$code]['discount'],
        ]);
        return $this->respond(true, 'Kode promo diterapkan');
    }

    /** Alias untuk compatibility */
    public function redeemPromo(Request $r)
    {
        return $this->applyPromo($r);
    }

    /** Nonaktifkan kode promo */
    public function removePromo()
    {
        Session::forget(['promo_code', 'promo_type', 'promo_discount']);
        return $this->respond(true, 'Kode promo dihapus');
    }

    /**
     * Pilih Pengiriman
     */
    public function selectShipping(Request $r)
    {
        $r->validate(['shipping_method_id' => 'required|integer']);

        $shipQ = ShippingMethod::query();
        if (Schema::hasColumn('shipping_methods', 'is_active')) {
            $shipQ->where('is_active', 1);
        }

        $method = $shipQ->findOrFail($r->shipping_method_id);
        Session::put('shipping_method_id', $method->id);
        Session::put('shipping_method', $method->code ?? 'KURIR_TOKO');

        return $this->respond(true, 'Metode pengiriman disimpan');
    }

    /** Alias untuk compatibility */
    public function saveShipping(Request $r)
    {
        return $this->selectShipping($r);
    }

    /**
     * Hitung total quantity
     */
    private function cartCount(): int
    {
        return Auth::check()
            ? Cart::where('user_id', Auth::id())->sum('quantity')
            : collect(Session::get('cart_items', []))->sum('quantity');
    }

    /**
     * Respon standar: JSON jika AJAX, flash+redirect jika non-AJAX
     */
    private function respond(
        bool $success,
        string $message,
        int $status = 200,
        ?string $error = null,
        array $data = []
    ) {
        if (request()->expectsJson()) {
            return response()->json(compact('success', 'message', 'error', 'data'), $status);
        }
        return back()->with($success ? 'success' : 'error', $message)->with($data);
    }
}
