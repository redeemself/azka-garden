<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Contact;
use App\Models\Promotion;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Exception;
use Illuminate\Database\QueryException;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang user.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login untuk melihat keranjang');
        }

        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        // Promo aktif di session
        $activePromo = null;
        $promoCode = session('promo_code');
        if ($promoCode) {
            $activePromo = Promotion::where('promo_code', $promoCode)->first();
        }

        $primaryAddress = $user->addresses()->where('is_primary', true)->first();
        $hasAddress = !empty($primaryAddress);

        return view('user.cart', compact('cartItems', 'activePromo', 'primaryAddress', 'hasAddress'));
    }

    /**
     * Tampilkan halaman konfirmasi pesanan dengan shipping cost yang benar
     */
    public function confirm(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login untuk melakukan konfirmasi pesanan');
        }

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang Anda kosong. Silahkan tambahkan produk terlebih dahulu.');
        }

        // Get shipping method from session or set default
        $shippingMethod = session('shipping_method', 'JNT');
        $paymentMethod = session('payment_method', 'CASH');
        $shippingAddressId = session('shipping_address_id');

        // Calculate shipping cost based on method
        $shippingCost = $this->calculateShippingCost($shippingMethod);

        // Store shipping cost in session for consistency
        session(['shipping_cost' => $shippingCost]);

        // Get user addresses
        $addresses = $user->addresses()->get();
        $selectedAddress = null;
        
        if ($addresses->count()) {
            $selectedAddress = $shippingAddressId 
                ? $addresses->where('id', $shippingAddressId)->first() 
                : ($addresses->where('is_primary', 1)->first() ?? $addresses->first());
        }

        // Calculate cart totals
        $subtotal = 0;
        $totalDiscount = 0;
        $originalPriceTotal = 0;
        
        $promoCode = session('promo_code');
        
        foreach ($cartItems as $item) {
            $product = $item->product;
            if (!$product) continue;
            
            $itemPromo = $item->promo_code ?? $promoCode;
            $promotion = $itemPromo ? Promotion::where('promo_code', $itemPromo)->first() : null;
            $discount = 0;
            $unitPrice = $product->price ?? 0;
            $qty = $item->quantity ?? 0;
            
            $originalPriceTotal += $unitPrice * $qty;
            
            if ($promotion) {
                if ($promotion->discount_type === 'percent') {
                    $percent = $promotion->discount_value ?: 10;
                    $discount = round($unitPrice * ($percent / 100));
                } elseif ($promotion->discount_type === 'fixed') {
                    $discount = min($promotion->discount_value ?: 0, $unitPrice);
                }
            }
            
            $discountedPrice = max(0, $unitPrice - $discount);
            $itemTotal = $discountedPrice * $qty;
            
            $subtotal += $itemTotal;
            $totalDiscount += $discount * $qty;
        }

        // Calculate final totals
        $handlingFee = 0;
        $paymentFee = 0;
        $totalBeforeTax = $subtotal + $handlingFee + $shippingCost + $paymentFee;
        $taxAmount = round($totalBeforeTax * 0.11); // PPN 11%
        $totalWithTax = $totalBeforeTax + $taxAmount;

        // Store order summary in session
        session([
            'order_summary' => [
                'subtotal' => $subtotal,
                'original_total' => $originalPriceTotal,
                'discount' => $totalDiscount,
                'handling_fee' => $handlingFee,
                'shipping_cost' => $shippingCost,
                'payment_fee' => $paymentFee,
                'tax_amount' => $taxAmount,
                'total' => $totalWithTax,
                'shipping_method' => $shippingMethod,
                'payment_method' => $paymentMethod
            ]
        ]);

        return view('user.orders.confirm', compact(
            'cartItems', 
            'shippingCost', 
            'selectedAddress', 
            'addresses',
            'subtotal',
            'totalDiscount',
            'originalPriceTotal',
            'taxAmount',
            'totalWithTax',
            'shippingMethod',
            'paymentMethod'
        ));
    }

    /**
     * Calculate shipping cost based on method - sesuai database shippings.sql
     */
    private function calculateShippingCost($method): float
    {
        // Shipping costs sesuai dengan database shippings.sql
        $costs = [
            'JNT' => 14000.00,          // ID 15 - J&T EZ
            'GOSEND' => 25000.00,       // ID 13 - GoSend Sameday  
            'JNE' => 12000.00,          // ID 14 - JNE REG
            'SICEPAT' => 15000.00,      // ID 16 - SiCepat BEST
            'KURIR_TOKO' => 15000.00,   // ID 11 - Default middle tier (5-10km)
            'AMBIL_SENDIRI' => 0.00     // ID 17 - Free pickup
        ];
        
        // Log untuk debugging
        Log::info('Calculating shipping cost', [
            'method' => $method,
            'cost' => $costs[$method] ?? 0,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $costs[$method] ?? 0;
    }

    /**
     * Get shipping cost via AJAX
     */
    public function getShippingCost(Request $request): JsonResponse
    {
        try {
            $method = $request->input('method');
            $cost = $this->calculateShippingCost($method);
            
            return response()->json([
                'success' => true,
                'cost' => $cost,
                'formatted_cost' => 'Rp' . number_format($cost, 0, ',', '.')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Process checkout from cart
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login untuk melakukan checkout');
        }

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Keranjang Anda kosong. Silahkan tambahkan produk terlebih dahulu.');
        }

        if ($request->has('shipping_method')) {
            session(['shipping_method' => $request->shipping_method]);
            // Recalculate shipping cost when method changes
            $shippingCost = $this->calculateShippingCost($request->shipping_method);
            session(['shipping_cost' => $shippingCost]);
        }

        if ($request->has('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }

        if ($request->has('shipping_address_id')) {
            session(['shipping_address_id' => $request->shipping_address_id]);
        }

        if (!session('shipping_method')) {
            session(['shipping_method' => 'JNT']); // Default ke JNT
            session(['shipping_cost' => $this->calculateShippingCost('JNT')]);
        }

        if (!session('payment_method')) {
            $paymentMethod = \DB::table('local_payment_methods')
                ->where('status', 1)
                ->first();

            if ($paymentMethod) {
                session(['payment_method' => $paymentMethod->code]);
            } else {
                session(['payment_method' => 'CASH']);
            }
        }

        return redirect()->route('user.cart.confirm');
    }

    /**
     * Update jumlah produk di keranjang
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            Log::info('Update cart item request received', [
                'id' => $id,
                'method' => $request->method(),
                'has_quantity' => $request->has('quantity'),
                'quantity' => $request->input('quantity'),
                'headers' => [
                    'accept' => $request->header('Accept'),
                    'content-type' => $request->header('Content-Type'),
                    'csrf' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
                ]
            ]);

            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk mengubah jumlah produk'
                ], 401);
            }

            $cartItem = Cart::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang Anda'
                ], 404);
            }

            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            if ($request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia'
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            $promo = $cartItem->promo_code ?? session('promo_code');
            $promotion = $promo ? Promotion::where('promo_code', $promo)->first() : null;

            $discount = 0;
            $unit_price = $product->price ?? 0;

            if ($promotion) {
                if ($promotion->discount_type === 'percent') {
                    $percent = $promotion->discount_value ?: 10;
                    $discount = round($unit_price * ($percent / 100));
                } elseif ($promotion->discount_type === 'fixed') {
                    $discount = min($promotion->discount_value ?: 0, $unit_price);
                }
            }

            $discounted_price = max(0, $unit_price - $discount);
            $item_total = $discounted_price * $request->quantity;

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diubah',
                'data' => [
                    'quantity' => $request->quantity,
                    'unit_price' => $unit_price,
                    'discounted_price' => $discounted_price,
                    'item_total' => $item_total,
                    'formatted_total' => 'Rp ' . number_format($item_total, 0, ',', '.'),
                ]
            ]);
        } catch (QueryException $e) {
            Log::error('Database error when updating cart item', [
                'cart_item_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database saat mengubah jumlah produk',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        } catch (Exception $e) {
            Log::error('Error when updating cart item', [
                'cart_item_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah jumlah produk',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Process update via POST for browsers that don't support PUT
     */
    public function updatePost(Request $request, $id): JsonResponse
    {
        Log::info('Update POST method received', [
            'id' => $id,
            'method' => $request->method(),
            'has_quantity' => $request->has('quantity'),
            'quantity' => $request->input('quantity'),
            'csrf' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        return $this->update($request, $id);
    }

    /**
     * Delete item from cart (JSON)
     */
    public function delete($id): JsonResponse
    {
        try {
            $user = Auth::user();

            Log::info('Delete cart item request received', [
                'id' => $id,
                'user_id' => $user ? $user->id : 'not authenticated',
                'method' => request()->method(),
                'headers' => [
                    'accept' => request()->header('Accept'),
                    'content-type' => request()->header('Content-Type'),
                    'csrf' => request()->header('X-CSRF-TOKEN') ? 'present' : 'missing'
                ]
            ]);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk menghapus produk dari keranjang'
                ], 401);
            }

            $cartItem = Cart::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan di keranjang Anda'
                ], 404);
            }

            $productId = $cartItem->product_id;
            $productName = $cartItem->product ? $cartItem->product->name : 'unknown';

            Log::info('Deleting cart item', [
                'user_id' => $user->id,
                'cart_item_id' => $id,
                'product_id' => $productId,
                'product_name' => $productName
            ]);

            $cartItem->delete();

            $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'data' => [
                    'cart_count' => $cartCount,
                    'deleted_id' => $id
                ]
            ]);
        } catch (QueryException $e) {
            Log::error('Database error when deleting cart item', [
                'cart_item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database saat menghapus produk',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        } catch (Exception $e) {
            Log::error('Error when deleting cart item', [
                'cart_item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk dari keranjang: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Hapus produk dari keranjang (redirect response)
     */
    public function remove($id): RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Anda harus login untuk menghapus produk dari keranjang');
            }

            $cartItem = Cart::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$cartItem) {
                return redirect()->back()
                    ->with('error', 'Produk tidak ditemukan di keranjang Anda.');
            }

            Log::info('Removing cart item (redirect)', [
                'user_id' => $user->id,
                'cart_item_id' => $id,
                'product_id' => $cartItem->product_id,
            ]);

            $cartItem->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');

        } catch (Exception $e) {
            Log::error('Error when removing cart item', [
                'cart_item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus produk dari keranjang.');
        }
    }

    /**
     * POST delete for browsers
     */
    public function deletePost(Request $request, $id): JsonResponse
    {
        Log::info('Delete POST method received', [
            'id' => $id,
            'method' => $request->method(),
            'csrf' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        return $this->delete($id);
    }

    /**
     * Simpan metode pengiriman ke session dan update shipping cost.
     */
    public function saveShipping(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'shipping_method' => 'required|string|max:50',
            ]);

            $shippingMethod = $request->shipping_method;
            $shippingCost = $this->calculateShippingCost($shippingMethod);

            session([
                'shipping_method' => $shippingMethod,
                'shipping_cost' => $shippingCost
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Metode pengiriman berhasil disimpan',
                'data' => [
                    'shipping_method' => $shippingMethod,
                    'shipping_cost' => $shippingCost,
                    'formatted_cost' => 'Rp' . number_format($shippingCost, 0, ',', '.')
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error saving shipping method', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan metode pengiriman',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Simpan metode pembayaran ke session.
     */
    public function savePayment(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'payment_method' => 'required|string|max:50',
            ]);

            session(['payment_method' => $request->payment_method]);

            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil disimpan'
            ]);
        } catch (Exception $e) {
            Log::error('Error saving payment method', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan metode pembayaran',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Tambah produk ke keranjang (versi minimal & stabil).
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'nullable|integer|min:1',
            ]);

            $user = Auth::user();
            if (!$user) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus login untuk menambahkan produk ke keranjang'
                    ], 401);
                }
                return redirect()->route('login')
                    ->with('error', 'Anda harus login untuk menambahkan produk ke keranjang');
            }

            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);

            $product = Product::find($productId);
            if (!$product) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan.'
                    ], 404);
                }
                return back()->with('error', 'Produk tidak ditemukan.');
            }

            if ($quantity > $product->stock) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia.'
                    ], 400);
                }
                return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }

            // Buat/Update cart
            $cartItem = Cart::firstOrNew([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $cartItem->quantity += $quantity;
            if ($cartItem->quantity > $product->stock) {
                $cartItem->quantity = $product->stock;
            }
            $cartItem->save();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke keranjang',
                    'data' => [
                        'cart_count' => Cart::where('user_id', $user->id)->sum('quantity'),
                        'item_id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                    ]
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambah ke keranjang');
        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambah produk: ' . $e->getMessage(),
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            return back()->with('error', 'Gagal menambah produk ke keranjang');
        }
    }

    /**
     * Apply promo code to cart
     */
    public function redeemPromo(Request $request)
    {
        try {
            $request->validate([
                'promo_code' => 'required|string|min:3|max:50',
            ]);

            $promotion = Promotion::where('promo_code', $request->promo_code)
                ->where('status', 1) // Using 'status' column instead of 'active'
                ->first();

            if (!$promotion) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode promo tidak valid atau sudah tidak aktif'
                    ], 400);
                }
                return back()->with('error', 'Kode promo tidak valid atau sudah tidak aktif');
            }

            session([
                'promo_code' => $promotion->promo_code,
                'promo_type' => $promotion->discount_type,
                'promo_discount' => $promotion->discount_value
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode promo berhasil diterapkan',
                    'data' => [
                        'promo_code' => $promotion->promo_code,
                        'promo_type' => $promotion->discount_type,
                        'promo_value' => $promotion->discount_value
                    ]
                ]);
            }

            return back()->with('success', 'Kode promo berhasil diterapkan');
        } catch (Exception $e) {
            Log::error('Error applying promo code', [
                'promo_code' => $request->promo_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menerapkan kode promo: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menerapkan kode promo');
        }
    }
}