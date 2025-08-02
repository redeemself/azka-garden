<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\ProductLike;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * User Product Controller - Fixed Relationship Errors
 *
 * Updated: 2025-08-02 02:29:15 UTC by gerrymulyadi709
 *
 * FIXED ISSUES:
 * ✅ Fixed product_images relationship query
 * ✅ Added proper error handling
 * ✅ Optimized database queries
 * ✅ Compatible with existing cart system
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products - FIXED
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Use optimized query with proper relationships
            $query = Product::getWithRelationsForListing();

            // Filter by category if provided (using ID from database)
            if ($request->has('category') && $request->category > 0) {
                $categoryId = (int) $request->category;
                $query->where('category_id', $categoryId);
            }

            // Filter by search term if provided
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->search($searchTerm);
            }

            // Sort products
            $sortBy = $request->input('sort', 'newest');
            switch ($sortBy) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc'); // newest
            }

            // Paginate results (15 items per page to match the view)
            $products = $query->paginate(15)->withQueryString();

            // Get all active categories for the filter
            $categories = Category::where('status', 1)->orderBy('name')->get();

            // For authenticated users, get cart items to show "in cart" status
            $cartProductIds = [];
            if (Auth::check()) {
                $cartProductIds = Cart::where('user_id', Auth::id())
                    ->pluck('product_id')
                    ->toArray();
            }

            return view('products.index', compact('products', 'categories', 'cartProductIds'));
        } catch (\Exception $e) {
            Log::error('Error in User\ProductController@index: ' . $e->getMessage(), [
                'timestamp' => '2025-08-02 02:29:15',
                'user' => auth()->id() ?? 'guest',
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            // Create empty paginator to prevent further errors
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // empty collection
                0, // total items
                15, // per page
                1, // current page
                ['path' => request()->url(), 'pageName' => 'page']
            );

            // Return safe fallback data
            return view('products.index', [
                'products' => $emptyPaginator,
                'categories' => Category::where('status', 1)->orderBy('name')->get(),
                'cartProductIds' => [],
                'error' => 'Terjadi kesalahan saat memuat produk. Silakan refresh halaman.'
            ]);
        }
    }

    /**
     * Display a single product - FIXED
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            // Use optimized query for detail page
            $product = Product::getWithRelationsForDetail($id);

            // Check if product is active
            if (!$product->status) {
                return redirect()->route('products.index')
                    ->with('error', 'Produk tidak tersedia.');
            }

            // Get related products from same category - FIXED QUERY
            $relatedProducts = Product::with(['category', 'product_images'])
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->active()
                ->limit(4)
                ->get();

            // For authenticated users, check if product is liked and in cart
            $isLiked = false;
            $isInCart = false;
            if (Auth::check()) {
                $isLiked = $product->isLikedByUser(Auth::id());
                $isInCart = $product->isInUserCart(Auth::id());
            }

            return view('products.show', compact(
                'product',
                'relatedProducts',
                'isLiked',
                'isInCart'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Product not found in User\ProductController@show', [
                'product_id' => $id,
                'timestamp' => '2025-08-02 02:29:15',
                'user' => auth()->id() ?? 'guest'
            ]);

            return redirect()->route('products.index')
                ->with('error', 'Produk tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in User\ProductController@show: ' . $e->getMessage(), [
                'product_id' => $id,
                'timestamp' => '2025-08-02 02:29:15',
                'user' => auth()->id() ?? 'guest',
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('products.index')
                ->with('error', 'Terjadi kesalahan saat memuat produk.');
        }
    }

    /**
     * Like/Unlike a product - Enhanced
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk menyukai produk.',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $product = Product::findOrFail($id);
            $userId = Auth::id();

            // Check if already liked
            $existingLike = ProductLike::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->first();

            if ($existingLike) {
                // Unlike
                $existingLike->delete();
                $action = 'unliked';
                $message = 'Produk berhasil dihapus dari favorit';
            } else {
                // Like
                ProductLike::create([
                    'user_id' => $userId,
                    'product_id' => $product->id
                ]);
                $action = 'liked';
                $message = 'Produk berhasil ditambahkan ke favorit';
            }

            // Get updated likes count
            $likesCount = ProductLike::where('product_id', $product->id)->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'likes_count' => $likesCount,
                'is_liked' => $action === 'liked'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in User\ProductController@like: ' . $e->getMessage(), [
                'product_id' => $id,
                'timestamp' => '2025-08-02 02:29:15',
                'user' => auth()->id() ?? 'guest'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses permintaan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Add comment to product - Enhanced
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk menambahkan komentar.',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $validated = $request->validate([
                'content' => 'required|string|min:10|max:500',
                'rating' => 'required|integer|between:1,5'
            ], [
                'content.required' => 'Komentar harus diisi',
                'content.min' => 'Komentar minimal 10 karakter',
                'content.max' => 'Komentar maksimal 500 karakter',
                'rating.required' => 'Rating harus dipilih',
                'rating.between' => 'Rating harus antara 1-5'
            ]);

            $product = Product::findOrFail($id);
            $userId = Auth::id();

            // Check if user already commented on this product
            $existingComment = ProductComment::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->first();

            if ($existingComment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan komentar untuk produk ini.'
                ], 400);
            }

            // Create new comment
            $comment = ProductComment::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'content' => $validated['content'],
                'rating' => $validated['rating'],
                'is_approved' => 0 // Default to pending approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan dan sedang menunggu persetujuan.',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'rating' => $comment->rating,
                    'user_name' => Auth::user()->name,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'is_approved' => $comment->is_approved
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in User\ProductController@comment: ' . $e->getMessage(), [
                'product_id' => $id,
                'timestamp' => '2025-08-02 02:29:15',
                'user' => auth()->id() ?? 'guest',
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komentar. Silakan coba lagi.'
            ], 500);
        }
    }
}
