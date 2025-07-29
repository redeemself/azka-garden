<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Promotion;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
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

        $promoCode = $request->input('promo_code') ?? session('promo_code');
        $promo = null;
        if ($promoCode) {
            $promo = Promotion::where('promo_code', $promoCode)
                ->where('status', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
            if ($promo) {
                session(['promo_code' => $promoCode]);
            } else {
                session()->forget('promo_code');
            }
        }

        $contacts = Contact::all();
        $banners = []; // Sesuaikan dengan query banner Anda jika ada

        // Kirim semua data ke view
        return view('user.products.index', [
            'products' => $products,
            'categories' => $categories,
            'promo' => $promo,
            'contacts' => $contacts,
            'banners' => $banners,
        ]);
    }

    public function show($id, Request $request)
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
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
            if ($promo) {
                session(['promo_code' => $promoCode]);
            } else {
                session()->forget('promo_code');
            }
        }

        $contacts = Contact::all();

        $comments = Review::where('product_id', $product->getKey())->with('user')->get();

        return view('user.products.show', [
            'product' => $product,
            'productImages' => $productImages,
            'promo' => $promo,
            'contacts' => $contacts,
            'comments' => $comments,
        ]);
    }

    /**
     * POST /products/{id}/comment
     * Tambahkan komentar produk
     */
    public function comment(Request $request, $id)
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
     */
    public function rekomendasiUntukmu(Request $request)
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