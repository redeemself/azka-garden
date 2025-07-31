<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Promotion;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Category;
use App\Models\Cart;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * User Product Controller
 *
 * Handles product listing, details, comments, and recommendations for users
 *
 * @updated 2025-07-30 04:24:14 by mulyadafa
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products with filters and pagination
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Ambil semua kategori untuk filter
        $categories = Category::where('status', 1)->orderBy('name')->get();

        $query = Product::with(['category', 'images'])->where('status', 1);

        // Filter kategori
        $category = $request->input('category');
        if ($category) {
            $query->where('category_id', $category);
        }

        // Filter pencarian nama produk
        $search = $request->input('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Pagination agar list produk tidak terlalu panjang
        $products = $query->paginate(12);

        // Perbaikan promo code: ambil dari session, bukan hanya dari input
        $promoCode = $request->input('promo_code') ?? session('promo_code');
        $promo = null;
        if ($promoCode) {
            $promo = Promotion::where('promo_code', $promoCode)
                ->where('status', true)
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->first();

            if ($promo) {
                session(['promo_code' => $promoCode]);
                session(['promo_type' => $promo->discount_type]);
                session(['promo_discount' => $promo->discount_value]);
            } else {
                session()->forget('promo_code');
                session()->forget('promo_type');
                session()->forget('promo_discount');
            }
        }

        $contacts = Contact::all();
        $banners = []; // Sesuaikan dengan query banner Anda jika ada

        // Ambil metode pengiriman aktif dan urutkan
        $shippingMethods = ShippingMethod::where('is_active', 1)->orderBy('sort_order')->get();

        // Ambil metode pengiriman yang dipilih dari session, atau null
        $selectedShipId = session('shipping_method_id');

        // Ambil isi keranjang user atau session
        if (Auth::check()) {
            $items = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartItems = session('cart_items') ?? (session('cartItems') ?? []);
            $items = collect($cartItems);
        }

        // Kirim semua data ke view
        return view('user.products.index', [
            'products' => $products,
            'categories' => $categories,
            'promo' => $promo,
            'contacts' => $contacts,
            'banners' => $banners,
            'cartItemCount' => $items->sum('quantity'), // total quantity keranjang
            'shippingMethods' => $shippingMethods,
            'selectedShipId' => $selectedShipId,
            'items' => $items,
        ]);
    }

    /**
     * Display the specified product with details and reviews
     *
     * @param int $id
     * @param Request $request
     * @return View
     */
    public function show($id, Request $request): View
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);

        $productImages = $product->images->filter(function ($img) {
            return !empty($img->image_url) && preg_match('/\.(jpg|jpeg|png)$/i', $img->image_url);
        });

        $promoCode = $request->input('promo_code') ?? session('promo_code');
        $promo = null;
        if ($promoCode) {
            $promo = Promotion::where('promo_code', $promoCode)
                ->where('status', true)
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->first();

            if ($promo) {
                session(['promo_code' => $promoCode]);
                session(['promo_type' => $promo->discount_type]);
                session(['promo_discount' => $promo->discount_value]);
            } else {
                session()->forget('promo_code');
                session()->forget('promo_type');
                session()->forget('promo_discount');
            }
        }

        $contacts = Contact::all();

        $comments = Review::where('product_id', $product->getKey())->with('user')->get();

        // Ambil isi keranjang user atau session
        if (Auth::check()) {
            $items = Cart::with('product')->where('user_id', Auth::id())->get();
        } else {
            $cartItems = session('cart_items') ?? (session('cartItems') ?? []);
            $items = collect($cartItems);
        }

        return view('user.products.show', [
            'product' => $product,
            'productImages' => $productImages,
            'promo' => $promo,
            'contacts' => $contacts,
            'comments' => $comments,
            'cartItemCount' => $items->sum('quantity'), // total quantity keranjang
        ]);
    }

    /**
     * POST /products/{id}/comment
     * Tambahkan komentar produk
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function comment(Request $request, $id): RedirectResponse|JsonResponse
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login untuk berkomentar.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk berkomentar.');
        }

        $product = Product::findOrFail($id);

        $review = new Review();
        $review->product_id = $product->getKey();
        $review->user_id = Auth::id();
        $review->comment = $request->input('comment');
        $review->rating = 5;
        $review->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $review->comment,
                'user' => Auth::user()->name,
                'created_at' => $review->created_at->diffForHumans(),
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Dapatkan rekomendasi produk acak yang belum pernah dibeli user
     * GET /user/products/rekomendasi
     *
     * @param Request $request
     * @return View
     */
    public function rekomendasiUntukmu(Request $request): View
    {
        $userId = Auth::id();
        $rekomendasiPerluDibeli = Product::where('stock', '>', 0)
            ->where('status', 1)
            ->inRandomOrder()
            ->whereDoesntHave('orders', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->limit(3)
            ->with(['category', 'images'])
            ->get();

        return view('user.products.rekomendasi', [
            'rekomendasiPerluDibeli' => $rekomendasiPerluDibeli
        ]);
    }
}
