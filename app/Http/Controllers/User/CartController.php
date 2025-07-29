<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
     *
     * @return View|RedirectResponse
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

        // Tambahkan informasi alamat untuk pengiriman
        $primaryAddress = $user->addresses()->where('is_primary', true)->first();
        $hasAddress = !empty($primaryAddress);

        // Tidak perlu hitung total di sini; perhitungan total dilakukan di Blade
        return view('user.cart', compact('cartItems', 'activePromo', 'primaryAddress', 'hasAddress'));
    }

    /**
     * Process checkout from cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View|RedirectResponse
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

        // Save shipping and payment method to session
        if ($request->has('shipping_method')) {
            session(['shipping_method' => $request->shipping_method]);
        }

        if ($request->has('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }

        if ($request->has('shipping_address_id')) {
            session(['shipping_address_id' => $request->shipping_address_id]);
        }

        // Check if shipping method and payment method are set
        if (!session('shipping_method')) {
            session(['shipping_method' => 'KURIR_TOKO']);
        }

        if (!session('payment_method')) {
            // Set default payment method from database if available
            $paymentMethod = \DB::table('local_payment_methods')
                ->where('status', 1)
                ->first();

            if ($paymentMethod) {
                session(['payment_method' => $paymentMethod->code]);
            } else {
                session(['payment_method' => 'CASH']);
            }
        }

        return view('User.orders.confirm', compact('cartItems'));
    }

    /**
     * Update jumlah produk di keranjang dengan improved error handling
     *
     * @param Request $request
     * @param int $id ID cart item yang akan diupdate
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Validate request
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

            // Find the cart item with explicit user check
            $cartItem = Cart::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang Anda'
                ], 404);
            }

            // Validasi stok produk
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Check product stock
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia'
                ], 400);
            }

            // Update quantity
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Hitung total dengan diskon
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
     * Delete item from cart with improved error handling (JSON response)
     * Supports both DELETE and POST methods for better compatibility
     *
     * @param int $id Cart item ID
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            $user = Auth::user();

            // Log request for debugging
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

            // Find the cart item with explicit user check
            $cartItem = Cart::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan di keranjang Anda'
                ], 404);
            }

            // Get product details before deletion for logging
            $productId = $cartItem->product_id;
            $productName = $cartItem->product ? $cartItem->product->name : 'unknown';

            // Log before deletion (optional but helpful for debugging)
            Log::info('Deleting cart item', [
                'user_id' => $user->id,
                'cart_item_id' => $id,
                'product_id' => $productId,
                'product_name' => $productName
            ]);

            // Perform the deletion
            $cartItem->delete();

            // Get updated cart count for the user
            $cartCount = Cart::where('user_id', $user->id)->count();

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
     * Supports form submission with POST method
     *
     * @param int $id
     * @return RedirectResponse
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

            // Log before deletion
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
     * Alternative delete endpoint that accepts POST method for compatibility
     * This helps with browsers or situations where DELETE method might not be supported
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deletePost(Request $request, $id): JsonResponse
    {
        Log::info('Delete POST method received', [
            'id' => $id,
            'csrf' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        // Simply call the delete method
        return $this->delete($id);
    }

    /**
     * Simpan metode pengiriman ke session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveShipping(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'shipping_method' => 'required|string|max:50',
            ]);

            session(['shipping_method' => $request->shipping_method]);

            return response()->json([
                'success' => true,
                'message' => 'Metode pengiriman berhasil disimpan'
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
     *
     * @param Request $request
     * @return JsonResponse
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
     * Tambah produk ke keranjang dengan validasi promo code dan diskon.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'promo_code' => 'nullable|string|max:50',
                'quantity' => 'nullable|integer|min:1',
            ]);

            $user = Auth::user();

            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda harus login untuk menambahkan produk ke keranjang'
                    ], 401);
                }
                return redirect()->route('login')
                    ->with('error', 'Anda harus login untuk menambahkan produk ke keranjang');
            }

            $productId = $request->input('product_id');
            $promoCode = trim($request->input('promo_code')) ?: session('promo_code');
            $quantity = $request->input('quantity', 1);
            $discount = 0;
            $discountMsg = '';

            // Pastikan $product adalah model, bukan Collection
            $product = Product::find($productId);

            if (!$product || !($product instanceof Product)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan.'
                    ], 404);
                }
                return back()->with('error', 'Produk tidak ditemukan.');
            }

            // Check stock
            if ($quantity > $product->stock) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah melebihi stok yang tersedia.'
                    ], 400);
                }
                return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }

            // Validasi dan hitung diskon dari promo
            $promotion = null;
            if ($promoCode) {
                $promotion = Promotion::where('promo_code', $promoCode)
                    ->where('active', true)
                    ->first();

                // Pastikan $promotion tidak null sebelum akses property discount_type
                if ($promotion && (method_exists($promotion, 'isValid') ? $promotion->isValid() : true)) {
                    if ($promotion->discount_type === 'fixed') {
                        // Diskon fixed tidak boleh melebihi harga produk
                        $discount = min($promotion->discount_value ?? 0, $product->price);
                        $discountMsg = "Diskon Rp " . number_format($discount, 0, ',', '.');
                    } elseif ($promotion->discount_type === 'percent') {
                        $percent = $promotion->discount_value ?: 10;
                        $discount = round($product->price * ($percent / 100));
                        $discountMsg = "Diskon {$percent}%";
                    }
                } else {
                    // Promo code dari newsletter?
                    $contact = Contact::where('email', $user->email)
                        ->where('promo_code', $promoCode)
                        ->first();
                    if ($contact) {
                        $discount = round($product->price * 0.10);
                        $discountMsg = "Diskon Newsletter 10%";
                    }
                }
            }

            // Cek dan update Cart dengan diskon yang benar
            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // Jika sudah ada, tambah quantity
                $cartItem->quantity = ($cartItem->quantity ?? 0) + $quantity;

                // Pastikan tidak melebihi stok
                if ($cartItem->quantity > $product->stock) {
                    $cartItem->quantity = $product->stock;
                }

                $cartItem->promo_code = $promoCode;
                $cartItem->discount = $discount;
                $cartItem->save();

                Log::info('Updated cart item quantity', [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $cartItem->quantity,
                    'promo_code' => $promoCode
                ]);
            } else {
                // Jika belum ada, buat baru
                $cartData = [
                    'user_id'    => $user->id,
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'promo_code' => $promoCode,
                    'discount'   => $discount,
                ];

                // Tambahkan interface_id jika model membutuhkannya
                if (in_array('interface_id', (new Cart)->getFillable())) {
                    $cartData['interface_id'] = 1;
                }

                $cartItem = Cart::create($cartData);

                Log::info('Added new item to cart', [
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'promo_code' => $promoCode
                ]);
            }

            // Simpan promo ke session
            session([
                'promo_code' => $promoCode,
                'promo_discount' => $discount
            ]);

            $message = 'Produk berhasil ditambahkan ke keranjang';
            if ($discount > 0) {
                $message .= ' dengan promo! ' . $discountMsg;
            } else if ($promoCode) {
                $message .= '. Kode promo tidak valid.';
            } else {
                $message .= '.';
            }

            // Return JSON response if requested
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'cart_count' => Cart::where('user_id', $user->id)->count(),
                        'item_id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                        'discount' => $discount
                    ]
                ]);
            }

            return back()->with('success', $message);

        } catch (Exception $e) {
            Log::error('Error adding product to cart', [
                'product_id' => $request->input('product_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan produk ke keranjang: ' . $e->getMessage(),
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Gagal menambahkan produk ke keranjang.');
        }
    }

    /**
     * Apply promo code to cart
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function redeemPromo(Request $request)
    {
        try {
            $request->validate([
                'promo_code' => 'required|string|min:3|max:50',
            ]);

            // Check if promo code exists
            $promotion = Promotion::where('promo_code', $request->promo_code)
                ->where('active', true)
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

            // Store promo in session
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
