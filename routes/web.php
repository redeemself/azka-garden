<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\AuthController as UserAuthController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\User\PromoController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\ServicesController;

// -----------------------------
// HOME & PUBLIC ROUTES
// -----------------------------
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/services', [PublicController::class, 'services'])->name('services.index');
Route::controller(PublicController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.submit');
});
Route::get('/products', [PublicController::class, 'products'])->name('products.index');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Product add to cart
Route::post('/products/{id}/add-to-cart', [ProductController::class, 'addToCart'])
    ->name('products.add-to-cart');

// Blog & Articles
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('artikel.index');
});

// Sitemap & Policies
Route::get('sitemap', [PublicController::class, 'sitemapHtml'])->name('sitemap.html');
Route::get('sitemap.xml', [PublicController::class, 'sitemapXml'])->name('sitemap.xml');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
Route::view('/privacy', 'policies.privacy')->name('privacy');
Route::view('/terms', 'policies.terms')->name('terms');
Route::view('/cookies', 'policies.cookies')->name('cookies');
Route::view('/return-policy', 'policies.return')->name('return.policy');
Route::view('/accessibility', 'policies.accessibility')->name('accessibility');

Route::post('/policy/accept', function (Request $request) {
    return redirect()->back()->with('success', 'Kebijakan privasi diterima.');
})->name('policy.accept');

Route::get('/policy/reset', function () {
    return view('policies.reset_confirmation');
})->name('policy.reset.form');

Route::post('/policy/reset', function (Request $request) {
    return redirect()->route('privacy')->with('success', 'Persetujuan kebijakan privasi telah direset.');
})->name('policy.reset');

// -----------------------------
// PROMO CODE ACTIVATION & CART UTILITIES
// -----------------------------
Route::middleware('web')->group(function () {
    // Promo code routes
    Route::post('/promo/activate',   [PromoController::class, 'activate'])->name('promo.activate');
    Route::post('/promo/deactivate', [PromoController::class, 'deactivate'])->name('promo.deactivate');

    // Cart count utilities
    Route::get('/get-cart-count', function () {
        if (auth()->check()) {
            $count = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
        } else {
            $cartItems = session('cart_items', []);
            $count = collect($cartItems)->sum('quantity');
        }

        // Update session with accurate count
        session(['cart_count' => $count]);

        return response()->json(['count' => $count]);
    });

    Route::post('/update-cart-count', function (Request $request) {
        $count = $request->input('count', 0);
        session(['cart_count' => $count]);
        return response()->json(['success' => true]);
    })->middleware('csrf');
});

// -----------------------------
// USER AUTH ROUTES
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('login',    [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('login',   [UserAuthController::class, 'login'])->name('login.user.submit');
});
Route::middleware('auth')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

// -----------------------------
// CART & CHECKOUT ROUTES
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Cart - Main Views
    Route::get('/cart',             [CartController::class, 'index'])->name('cart.index');
    Route::get('/user/cart',        [CartController::class, 'index'])->name('user.cart.index');

    // Cart - Item Management (Enhanced with increment/decrement)
    Route::post('/cart/add',        [CartController::class, 'add'])->name('cart.add');
    Route::match(['patch', 'post'], '/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::match(['patch', 'post'], '/user/cart/update/{id}', [CartController::class, 'update'])->name('user.cart.update');
    Route::delete('/cart/{id}',     [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/user/cart/remove/{id}', [CartController::class, 'remove'])->name('user.cart.remove');

    // Cart - New Clear Cart Functionality
    Route::post('/cart/clear',      [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/user/cart/clear', [CartController::class, 'clear'])->name('user.cart.clear');

    // Promo on Cart
    Route::post('/cart/apply-promo',   [CartController::class, 'applyPromo'])->name('user.cart.apply-promo');
    Route::post('/cart/redeem-promo',  [CartController::class, 'redeemPromo'])->name('cart.redeem-promo');
    Route::delete('/cart/remove-promo', [CartController::class, 'removePromo'])->name('user.cart.remove-promo');

    // Shipping
    Route::post('/cart/select-shipping', [CartController::class, 'selectShipping'])->name('cart.select-shipping');
    Route::post('/cart/save-shipping',  [CartController::class, 'saveShipping'])->name('cart.save-shipping');

    // Prepare checkout AJAX
    Route::post('/user/cart/prepare-checkout', function (Request $request) {
        try {
            if (!$request->has('items') || !$request->has('summary')) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap'], 400);
            }
            $cartData = [
                'items'      => $request->input('items', []),
                'summary'    => $request->input('summary', []),
                'user'       => $request->input('user', 'Guest'),
                'timestamp'  => $request->input('timestamp', now()->toISOString()),
                'prepared_at' => now()->toISOString(),
                'prepared_by' => auth()->user()->name ?? 'Guest',
                'user_id'    => auth()->id()
            ];
            session(['cart_data' => $cartData]);
            \Log::info('Checkout data prepared successfully', [
                'user_id' => auth()->id(),
                'items_count' => count($cartData['items']),
                'timestamp' => '2025-08-01 05:21:01',
                'prepared_by' => 'DenuJanuari'
            ]);
            return response()->json(['success' => true, 'message' => 'Data checkout berhasil disiapkan']);
        } catch (\Exception $e) {
            \Log::error('Checkout preparation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => '2025-08-01 05:21:01',
                'error_by' => 'DenuJanuari'
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    })->name('cart.prepare-checkout');

    // Unified checkout via controller
    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
        Route::get('/',        'index')->name('index');    // GET  /checkout
        Route::post('/prepare', 'prepare')->name('prepare');
        Route::post('/process', 'process')->name('process');
        Route::get('/confirm', 'confirm')->name('confirm');
        Route::get('/success/{order}', 'success')->name('success');
        Route::get('/failed',  'failed')->name('failed');
    });
});

// -----------------------------
// AUTHENTICATED USER ROUTES
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // User profile routes
    Route::prefix('user/profile')->name('user.profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::get('/password', 'editPassword')->name('password.edit');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::delete('/delete', 'delete')->name('delete');
    });
    
    // User product routes
    Route::get('/user/products/{id}', [ProductController::class, 'show'])->name('user.products.show');
    
    Route::post('/products/{id}/like',    [ProductController::class, 'like'])->name('products.like');
    Route::post('/products/{id}/comment', [ProductController::class, 'comment'])->name('products.comment');
    
    // Address management
    Route::prefix('user/address')->name('user.address.')->controller(AddressController::class)->group(function () {
        Route::get('/',     'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{address}/edit', 'edit')->name('edit');
        Route::put('/{address}', 'update')->name('update');
        Route::delete('/{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });
    Route::post('/address/update-coords', [AddressController::class, 'updateCoords'])->name('address.updateCoords');
    
    // Order management
    Route::get('/user/orders', [OrderController::class, 'index'])->name('user.orders.index');
    Route::patch('user/orders/{order}/expire',   [OrderController::class, 'expire'])->name('user.orders.expire.global');
    Route::patch('user/orders/{order}/cancel',   [OrderController::class, 'cancel'])->name('user.orders.cancel.global');
    Route::patch('user/orders/{order}/complete', [OrderController::class, 'complete'])->name('user.orders.complete.global');

    // Tambahan: Riwayat dan detail pesanan
    Route::get('/user/orders/history', [OrderController::class, 'history'])->name('user.orders.history.index');
    Route::get('/user/orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
});

// -----------------------------
// ADMIN ROUTES
// -----------------------------
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Auth Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
        
        // Admin Registration Routes
        Route::get('/register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');
    });

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/password', [AdminProfileController::class, 'editPassword'])->name('profile.password.edit');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

// -----------------------------
// DEV ROUTES (for development only)
// -----------------------------
if (app()->environment('local')) {
    Route::prefix('dev')->group(function () {
        Route::get('/test', [DevController::class, 'test'])->name('dev.test');
    });
}