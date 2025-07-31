<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingMethod;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang
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
     * Tambah produk ke keranjang (AJAX/normal)
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
     * Hapus item
     */
    public function remove($id)
    {
        if (Auth::check()) {
            Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        } else {
            $items = collect(Session::get('cart_items', []))->reject(fn($it) => $it['id'] == $id)->values();
            Session::put('cart_items', $items->all());
        }

        return $this->respond(true, 'Item dihapus');
    }

    /**
     * Aktifkan promo
     */
    public function applyPromo(Request $r)
    {
        $r->validate(['promo_code' => 'required|string|max:50']);
        $valid = [
            'JULI10' => ['type' => 'percent', 'discount' => 10],
            'HEMAT5' => ['type' => 'fixed', 'discount' => 5000],
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
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }
        return collect(Session::get('cart_items', []))->sum('quantity');
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
        return back()->with($success ? 'success' : 'error', $message)
            ->with($data);
    }
}
