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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * User Product Controller
 *
 * Handles product listing, details, comments, and recommendations for users
 *
 * @updated 2025-07-31 17:18:17 by DenuJanuari
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
        $this->logPageAccess('product_listing', $request);

        // Get filtered products
        $products = $this->getFilteredProducts($request);

        // Get categories for filter
        $categories = $this->getActiveCategories();

        // Process promo code
        $promo = $this->processPromoCode($request);

        // Get additional data
        $additionalData = $this->getAdditionalIndexData();

        // Get cart data
        $cartData = $this->getCartData();

        $this->logIndexDataPrepared($products, $promo, $cartData);

        return view('user.products.index', array_merge([
            'products' => $products,
            'categories' => $categories,
            'promo' => $promo,
        ], $additionalData, $cartData));
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
        $this->logPageAccess('product_detail', $request, ['product_id' => $id]);

        // Get product with relations
        $product = $this->getProductWithRelations($id);

        // Get product images
        $productImages = $this->getProductImages($product);

        // Process promo code
        $promo = $this->processPromoCode($request);

        // Get additional show data
        $additionalData = $this->getAdditionalShowData($product);

        // Get cart data
        $cartData = $this->getCartData();

        // Check if product is in cart
        $isInCart = $this->checkProductInCart($product->id);

        $this->logShowDataPrepared($product, $promo, $isInCart);

        return view('user.products.show', array_merge([
            'product' => $product,
            'productImages' => $productImages,
            'promo' => $promo,
            'isInCart' => $isInCart,
        ], $additionalData, $cartData));
    }

    /**
     * Add product to cart (single item only)
     * POST /products/{id}/add-to-cart
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|RedirectResponse
     * @updated 2025-07-31 17:18:17 by DenuJanuari
     */
    public function addToCart(Request $request, int $id)
    {
        try {
            Log::info('Product add to cart initiated', [
                'product_id' => $id,
                'user_id' => auth()->id(),
                'timestamp' => '2025-07-31 17:18:17',
                'user' => 'DenuJanuari'
            ]);

            // Validate input
            $validationResult = $this->validateAddToCartRequest($request);
            if (!$validationResult['success']) {
                return $this->handleValidationError($request, $validationResult['errors'], $id);
            }

            // Get product
            $product = $this->getProductForCart($id);
            if (!$product) {
                return $this->handleProductNotFound($request);
            }

            // Check stock
            if (!$this->checkProductStock($product)) {
                return $this->handleOutOfStock($request, $product);
            }

            // Check if already in cart
            if ($this->checkProductInCart($product->id)) {
                return $this->handleAlreadyInCart($request, $product);
            }

            // Add to cart
            $result = $this->performAddToCart($request, $product);

            return $this->handleAddToCartSuccess($request, $product, $result);
        } catch (\Exception $e) {
            return $this->handleAddToCartError($request, $e, $id);
        }
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
        $this->logPageAccess('product_comment', $request, ['product_id' => $id]);

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        if (!Auth::check()) {
            $this->logUnauthenticatedAccess('comment', $id, $request);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login untuk berkomentar.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk berkomentar.');
        }

        $product = Product::findOrFail($id);
        $review = $this->createProductReview($product, $request);

        $this->logCommentCreated($product, $review);

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
     * POST /products/{id}/like
     * Toggle like untuk produk
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|RedirectResponse
     * @updated 2025-07-31 17:18:17 by DenuJanuari
     */
    public function like(Request $request, $id): JsonResponse|RedirectResponse
    {
        if (!Auth::check()) {
            $this->logUnauthenticatedAccess('like', $id, $request);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Silakan login untuk menyukai produk.'], 401);
            }
            return redirect()->back()->with('error', 'Silakan login untuk menyukai produk.');
        }

        $product = Product::findOrFail($id);

        Log::info('Product like toggled', [
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil disukai',
                'liked' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil disukai!');
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

        Log::info('Product recommendations requested', [
            'user_id' => $userId,
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        $rekomendasiPerluDibeli = $this->getProductRecommendations($userId);

        Log::info('Product recommendations generated', [
            'user_id' => $userId,
            'recommendations_count' => $rekomendasiPerluDibeli->count(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        return view('user.products.rekomendasi', [
            'rekomendasiPerluDibeli' => $rekomendasiPerluDibeli
        ]);
    }

    // ===== HELPER METHODS FOR INDEX =====

    /**
     * Get filtered products based on request parameters
     */
    private function getFilteredProducts(Request $request): LengthAwarePaginator
    {
        $query = $this->buildProductQuery();

        $this->applyCategoryFilter($query, $request);
        $this->applySearchFilter($query, $request);

        return $query->paginate(12);
    }

    /**
     * Build base product query
     */
    private function buildProductQuery(): Builder
    {
        return Product::with(['category', 'images'])->where('status', 1);
    }

    /**
     * Apply category filter to query
     */
    private function applyCategoryFilter(Builder $query, Request $request): void
    {
        $category = $request->input('category');
        if ($category) {
            $query->where('category_id', $category);
        }
    }

    /**
     * Apply search filter to query
     */
    private function applySearchFilter(Builder $query, Request $request): void
    {
        $search = $request->input('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
    }

    /**
     * Get active categories for filter
     */
    private function getActiveCategories(): Collection
    {
        return Category::where('status', 1)->orderBy('name')->get();
    }

    /**
     * Get additional data for index page
     */
    private function getAdditionalIndexData(): array
    {
        return [
            'contacts' => Contact::all(),
            'banners' => [],
            'shippingMethods' => ShippingMethod::where('is_active', 1)->orderBy('sort_order')->get(),
            'selectedShipId' => session('shipping_method_id'),
        ];
    }

    // ===== HELPER METHODS FOR SHOW =====

    /**
     * Get product with relations
     */
    private function getProductWithRelations(int $id): Product
    {
        return Product::with(['category', 'images'])->findOrFail($id);
    }

    /**
     * Get filtered product images
     */
    private function getProductImages(Product $product): Collection
    {
        return $product->images->filter(function ($img) {
            return !empty($img->image_url) && preg_match('/\.(jpg|jpeg|png)$/i', $img->image_url);
        });
    }

    /**
     * Get additional data for show page
     */
    private function getAdditionalShowData(Product $product): array
    {
        return [
            'contacts' => Contact::all(),
            'comments' => Review::where('product_id', $product->getKey())->with('user')->get(),
        ];
    }

    // ===== PROMO PROCESSING =====

    /**
     * Process promo code from request or session
     */
    private function processPromoCode(Request $request): ?Promotion
    {
        $promoCode = $this->getPromoCodeFromRequestOrSession($request);

        if (!$promoCode) {
            return null;
        }

        $promo = $this->findValidPromotion($promoCode);

        $this->handlePromoValidation($promo, $promoCode);

        return $promo;
    }

    /**
     * Get promo code from request or session
     */
    private function getPromoCodeFromRequestOrSession(Request $request): ?string
    {
        return $request->input('promo_code') ?? session('promo_code');
    }

    /**
     * Find valid promotion by code
     */
    private function findValidPromotion(string $promoCode): ?Promotion
    {
        return Promotion::where('promo_code', $promoCode)
            ->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();
    }

    /**
     * Handle promo validation and session storage
     */
    private function handlePromoValidation(?Promotion $promo, string $promoCode): void
    {
        if ($promo) {
            $this->storePromoInSession($promo);
            $this->logPromoValidated($promo);
        } else {
            $this->clearPromoFromSession();
            $this->logInvalidPromo($promoCode);
        }
    }

    /**
     * Store promo data in session
     */
    private function storePromoInSession(Promotion $promo): void
    {
        session([
            'promo_code' => $promo->promo_code,
            'promo_type' => $promo->discount_type,
            'promo_discount' => $promo->discount_value,
        ]);
    }

    /**
     * Clear promo data from session
     */
    private function clearPromoFromSession(): void
    {
        session()->forget(['promo_code', 'promo_type', 'promo_discount']);
    }

    // ===== CART OPERATIONS =====

    /**
     * Get cart data for views
     */
    private function getCartData(): array
    {
        $items = $this->getCartItems();

        return [
            'cartItemCount' => $items->sum('quantity'),
            'items' => $items,
        ];
    }

    /**
     * Get cart items for current user
     */
    private function getCartItems(): Collection
    {
        if (Auth::check()) {
            return Cart::with('product')->where('user_id', Auth::id())->get();
        }

        $cartItems = session('cart_items') ?? (session('cartItems') ?? []);
        return collect($cartItems);
    }

    /**
     * Get product recommendations for user
     */
    private function getProductRecommendations(int $userId): Collection
    {
        return Product::where('stock', '>', 0)
            ->where('status', 1)
            ->inRandomOrder()
            ->whereDoesntHave('orders', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->limit(3)
            ->with(['category', 'images'])
            ->get();
    }

    /**
     * Create product review
     */
    private function createProductReview(Product $product, Request $request): Review
    {
        $review = new Review();
        $review->product_id = $product->getKey();
        $review->user_id = Auth::id();
        $review->comment = $request->input('comment');
        $review->rating = 5;
        $review->save();

        return $review;
    }

    // ===== EXISTING HELPER METHODS (UNCHANGED) =====

    /**
     * Validate add to cart request
     */
    private function validateAddToCartRequest(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1|max:1',
            'price' => 'sometimes|integer|min:0',
        ], [
            'quantity.max' => 'Hanya dapat menambahkan 1 produk ke keranjang',
            'quantity.min' => 'Jumlah minimal adalah 1',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        return ['success' => true];
    }

    /**
     * Get product for cart operation
     */
    private function getProductForCart(int $id): ?Product
    {
        return Product::select(['id', 'name', 'price', 'image_url', 'stock'])
            ->where('status', 1)
            ->find($id);
    }

    /**
     * Check if product has sufficient stock
     */
    private function checkProductStock(Product $product): bool
    {
        return $product->stock >= 1;
    }

    /**
     * Check if product already exists in cart
     */
    private function checkProductInCart(int $productId): bool
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->exists();
        }

        $cartItems = session('cart_items', []);
        foreach ($cartItems as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $productId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Perform the actual add to cart operation
     */
    private function performAddToCart(Request $request, Product $product): array
    {
        $quantity = 1;
        $codePrice = (int) $request->input('price', 0);
        $finalPrice = $codePrice > 0 ? $codePrice : $product->price;

        if (Auth::check()) {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'price' => $finalPrice,
                'quantity' => $quantity,
                'name' => $product->name,
                'image' => $product->image_url,
            ]);

            $this->logCartAddSuccess($product, $quantity, $finalPrice);
        } else {
            $this->addToSessionCart($product, $quantity, $finalPrice);
        }

        return [
            'cart_count' => $this->cartCount(),
            'product_name' => $product->name,
            'quantity' => $quantity
        ];
    }

    /**
     * Add product to session cart
     */
    private function addToSessionCart(Product $product, int $quantity, float $finalPrice): void
    {
        $session = collect(Session::get('cart_items', []));

        $session->push([
            'id' => uniqid(),
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $finalPrice,
            'quantity' => $quantity,
            'image' => $product->image_url,
        ]);

        Session::put('cart_items', $session->all());

        Log::info('Product added to session cart successfully', [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $finalPrice,
            'session_items_count' => $session->count(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Get cart item count
     */
    private function cartCount(): int
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }

        $cartItems = Session::get('cart_items', []);
        return collect($cartItems)->sum('quantity');
    }

    // ===== LOGGING METHODS =====

    /**
     * Log page access
     */
    private function logPageAccess(string $page, Request $request, array $context = []): void
    {
        Log::info("Page accessed: {$page}", array_merge([
            'user_id' => auth()->id(),
            'request_params' => $request->only(['search', 'category', 'promo_code']),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ], $context));
    }

    /**
     * Log index data prepared
     */
    private function logIndexDataPrepared(LengthAwarePaginator $products, ?Promotion $promo, array $cartData): void
    {
        Log::info('Product listing data prepared', [
            'products_count' => $products->count(),
            'total_products' => $products->total(),
            'active_promo' => $promo ? $promo->promo_code : null,
            'cart_items_count' => $cartData['cartItemCount'],
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Log show data prepared
     */
    private function logShowDataPrepared(Product $product, ?Promotion $promo, bool $isInCart): void
    {
        Log::info('Product detail data prepared', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'is_in_cart' => $isInCart,
            'active_promo' => $promo ? $promo->promo_code : null,
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Log promo validated
     */
    private function logPromoValidated(Promotion $promo): void
    {
        Log::info('Promo code validated and stored in session', [
            'promo_code' => $promo->promo_code,
            'discount_type' => $promo->discount_type,
            'discount_value' => $promo->discount_value,
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Log invalid promo
     */
    private function logInvalidPromo(string $promoCode): void
    {
        Log::warning('Invalid promo code attempted', [
            'promo_code' => $promoCode,
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Log unauthenticated access
     */
    private function logUnauthenticatedAccess(string $action, int $productId, Request $request): void
    {
        Log::warning("Unauthenticated {$action} attempt", [
            'product_id' => $productId,
            'ip' => $request->ip(),
            'timestamp' => '2025-07-31 17:18:17'
        ]);
    }

    /**
     * Log comment created
     */
    private function logCommentCreated(Product $product, Review $review): void
    {
        Log::info('Product comment created successfully', [
            'product_id' => $product->id,
            'review_id' => $review->id,
            'user_id' => Auth::id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    /**
     * Log cart add success
     */
    private function logCartAddSuccess(Product $product, int $quantity, float $finalPrice): void
    {
        Log::info('Product added to cart successfully', [
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'quantity' => $quantity,
            'price' => $finalPrice,
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);
    }

    // ===== ERROR HANDLING METHODS (UNCHANGED) =====

    private function handleValidationError(Request $request, $errors, int $id)
    {
        Log::warning('Add to cart validation failed', [
            'errors' => $errors->toArray(),
            'product_id' => $id,
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'error' => $errors->first(),
                'data' => []
            ], 422);
        }

        return back()->withErrors($errors)->withInput()->with('error', $errors->first());
    }

    private function handleProductNotFound(Request $request)
    {
        Log::warning('Product not found for add to cart', [
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'error' => 'Product not found',
                'data' => []
            ], 404);
        }

        return back()->with('error', 'Produk tidak ditemukan');
    }

    private function handleOutOfStock(Request $request, Product $product)
    {
        Log::warning('Insufficient stock for add to cart', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'available_stock' => $product->stock,
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak tersedia',
                'error' => 'Out of stock',
                'data' => []
            ], 400);
        }

        return back()->with('error', 'Stok produk tidak tersedia');
    }

    private function handleAlreadyInCart(Request $request, Product $product)
    {
        Log::warning('Product already in cart', [
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di keranjang',
                'error' => 'Already in cart',
                'data' => ['cart_count' => $this->cartCount()]
            ], 400);
        }

        return back()->with('error', 'Produk sudah ada di keranjang');
    }

    private function handleAddToCartSuccess(Request $request, Product $product, array $result)
    {
        Log::info('Product successfully added to cart', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'cart_count' => $result['cart_count'],
            'user_id' => auth()->id(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'error' => null,
                'data' => $result
            ], 200);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    private function handleAddToCartError(Request $request, \Exception $e, int $id)
    {
        Log::error('Error adding product to cart', [
            'product_id' => $id,
            'user_id' => auth()->id(),
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'timestamp' => '2025-07-31 17:18:17',
            'user' => 'DenuJanuari'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'data' => []
            ], 500);
        }

        return back()->with('error', 'Terjadi kesalahan saat menambahkan produk ke keranjang');
    }
}
