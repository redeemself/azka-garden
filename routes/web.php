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

// ADDED: Product add to cart route - Updated 2025-07-31 19:34:00 by DenuJanuari
Route::post('/products/{id}/add-to-cart', [ProductController::class, 'addToCart'])->name('products.add-to-cart');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('artikel.index');
});
Route::get('sitemap', [PublicController::class, 'sitemapHtml'])->name('sitemap.html');
Route::get('sitemap.xml', [PublicController::class, 'sitemapXml'])->name('sitemap.xml');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
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
// PROMO CODE ACTIVATION & DEACTIVATION (Web middleware)
// -----------------------------
Route::middleware(['web'])->group(function () {
    Route::post('/promo/activate', [PromoController::class, 'activate'])->name('promo.activate');
    Route::post('/promo/deactivate', [PromoController::class, 'deactivate'])->name('promo.deactivate');
});

// -----------------------------
// USER AUTH ROUTES (guest middleware)
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [UserAuthController::class, 'login'])->name('login.user.submit');
});
Route::middleware('auth')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

// -----------------------------
// CART & CHECKOUT ROUTES - ENHANCED AND CONSOLIDATED
// Updated: 2025-07-31 19:34:00 by DenuJanuari
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/user/cart', [CartController::class, 'index'])->name('user.cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    // Cart update routes (support both PATCH and POST)
    Route::match(['patch', 'post'], '/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::match(['patch', 'post'], '/user/cart/update/{id}', [CartController::class, 'update'])->name('user.cart.update');

    // Cart remove routes
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/user/cart/remove/{id}', [CartController::class, 'remove'])->name('user.cart.remove');

    // Promo routes for cart
    Route::post('/cart/apply-promo', [CartController::class, 'applyPromo'])->name('user.cart.apply-promo');
    Route::post('/cart/redeem-promo', [CartController::class, 'redeemPromo'])->name('cart.redeem-promo');
    Route::delete('/cart/remove-promo', [CartController::class, 'removePromo'])->name('user.cart.remove-promo');

    // Shipping routes
    Route::post('/cart/select-shipping', [CartController::class, 'selectShipping'])->name('cart.select-shipping');
    Route::post('/cart/save-shipping', [CartController::class, 'saveShipping'])->name('cart.save-shipping');
    Route::post('/cart/update-shipping', [CartController::class, 'saveShipping'])->name('cart.update-shipping');

    // Checkout routes - FIXED AND ENHANCED
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('user.cart.checkout');
    Route::get('/cart/proceed-checkout', [CartController::class, 'proceedToCheckout'])->name('cart.proceed-checkout');

    // ENHANCED CHECKOUT INTEGRATION - Added: 2025-07-31 19:34:00 by DenuJanuari
    // Checkout preparation route for AJAX data transfer
    // ENHANCED CHECKOUT INTEGRATION - Fixed: 2025-07-31 19:36:09 by DenuJanuari
    // Simplified checkout preparation route with better error handling
    Route::post('/user/cart/prepare-checkout', function (Request $request) {
        try {
            // Basic validation - don't be too strict
            if (!$request->has('items') || !$request->has('summary')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak lengkap'
                ], 400);
            }

            // Store cart data in session with minimal processing
            $cartData = [
                'items' => $request->input('items', []),
                'summary' => $request->input('summary', []),
                'user' => $request->input('user', 'Guest'),
                'timestamp' => $request->input('timestamp', now()->toISOString()),
                'prepared_at' => now()->toISOString(),
                'prepared_by' => auth()->user()->name ?? 'Guest',
                'user_id' => auth()->id()
            ];

            session(['cart_data' => $cartData]);

            // Simple logging
            \Log::info('Checkout data prepared successfully', [
                'user_id' => auth()->id(),
                'items_count' => count($cartData['items']),
                'timestamp' => '2025-07-31 19:36:09'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data checkout berhasil disiapkan',
                'data' => [
                    'items_count' => count($cartData['items']),
                    'prepared_at' => $cartData['prepared_at']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Checkout preparation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'timestamp' => '2025-07-31 19:36:09'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    })->name('cart.prepare-checkout');

    // MAIN CHECKOUT ROUTE - Simple view for data display
    Route::get('/checkout', function () {
        return view('User.checkout', [
            'pageTitle' => 'Checkout - Azka Garden',
            'currentUser' => auth()->user()->name ?? 'Guest',
            'timestamp' => now()->toISOString()
        ]);
    })->name('checkout.index');

    // Modern Checkout Routes - Controller-based for future expansion
    Route::prefix('checkout')->name('user.checkout.')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('index');                    // GET /checkout -> user.checkout.index
        Route::post('/create', 'create')->name('create');           // POST /checkout/create -> user.checkout.create  
        Route::post('/process', 'process')->name('process');        // POST /checkout/process -> user.checkout.process
        Route::get('/confirm', 'confirm')->name('confirm');         // GET /checkout/confirm -> user.checkout.confirm
        Route::get('/success/{order}', 'success')->name('success'); // GET /checkout/success/{order} -> user.checkout.success
        Route::get('/failed', 'failed')->name('failed');           // GET /checkout/failed -> user.checkout.failed
    });

    // Legacy checkout routes for backward compatibility
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Backward compatibility routes for user cart
    Route::post('/user/cart/add', [CartController::class, 'add'])->name('user.cart.add');
});

// -----------------------------
// AUTHENTICATED USER ROUTES
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/products/{id}/comment', [ProductController::class, 'comment'])->name('products.comment');
    Route::prefix('user/address')->name('user.address.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{address}/edit', 'edit')->name('edit');
        Route::put('/{address}', 'update')->name('update');
        Route::delete('/{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });
    Route::post('/address/update-coords', [AddressController::class, 'updateCoords'])->name('address.updateCoords');
    Route::patch('user/orders/{order}/expire', [OrderController::class, 'expire'])->name('user.orders.expire.global');
    Route::patch('user/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel.global');
    Route::patch('user/orders/{order}/complete', [OrderController::class, 'complete'])->name('user.orders.complete.global');
});

// -----------------------------
// USER DASHBOARD ROUTES
// -----------------------------
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.profile.index'))->name('home');
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
        Route::post('confirm-roles', 'confirmRoles')->name('confirmRoles');
    });
    Route::prefix('addresses')->name('addresses.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{address}/edit', 'edit')->name('edit');
        Route::put('{address}', 'update')->name('update');
        Route::delete('{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });
    Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('redeem', 'redeemForm')->name('redeem.form');
        Route::post('redeem', 'redeemPromo')->name('redeem');
        Route::get('{id}', 'show')->name('show');
    });
    Route::prefix('payment')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('process', 'process')->name('process');
        Route::get('success/{order}', 'success')->name('success');
        Route::get('failed/{order?}', 'failed')->name('failed');
        Route::post('webhook', 'webhook')->name('webhook');
    });
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('orders.index');
        Route::get('history', 'history')->name('history.index');
        Route::post('create', 'create')->name('create');
        Route::get('checkout/success/{order}', 'checkoutSuccess')->name('checkout.success');
        Route::get('{order}', 'show')->name('show');
        Route::post('{order}/pay', 'pay')->name('pay');
        Route::post('{order}/cancel', 'cancel')->name('cancel');
        Route::post('{order}/expire', 'expire')->name('expire');
        Route::post('{order}/complete', 'complete')->name('complete');
        Route::post('{order}/finish', 'finish')->name('finish');
        Route::post('cancel-confirm', 'cancelConfirm')->name('cancelConfirm');
        Route::post('cancel-draft', 'cancelDraft')->name('cancel-draft');
        Route::post('clear_expired', 'clearExpired')->name('clear_expired');
    });
});

// -----------------------------
// ADMIN AUTH ROUTES
// -----------------------------
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AdminAuthController::class, 'register'])->name('register.submit');
});
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::controller(AdminProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
    });
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// -----------------------------
// DEVELOPER AUTH & DASHBOARD
// -----------------------------
Route::prefix('dev')->name('dev.')->middleware('guest:developer')->group(function () {
    Route::get('register', [DevController::class, 'showRegister'])->name('register');
    Route::post('register', [DevController::class, 'register'])->name('register.submit');
    Route::get('login', [DevController::class, 'showLogin'])->name('login');
    Route::post('login', [DevController::class, 'login'])->name('login.submit');
});
Route::prefix('dev')->name('dev.')->middleware(['auth:developer', 'developer'])->group(function () {
    Route::get('/', [DevController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [DevController::class, 'profile'])->name('profile');
    Route::put('profile', [DevController::class, 'updateProfile'])->name('profile.update');
    Route::post('logout', [DevController::class, 'logout'])->name('logout');
});

// -----------------------------
// DEBUG ROUTES (for development only)
// Updated: 2025-07-31 19:34:00 by DenuJanuari
// -----------------------------
if (config('app.debug')) {
    Route::get('/debug/routes', function () {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
            ];
        });

        return response()->json([
            'total_routes' => $routes->count(),
            'checkout_routes' => $routes->filter(function ($route) {
                return str_contains($route['name'] ?? '', 'checkout') || str_contains($route['uri'], 'checkout');
            })->values(),
            'cart_routes' => $routes->filter(function ($route) {
                return str_contains($route['name'] ?? '', 'cart') || str_contains($route['uri'], 'cart');
            })->values(),
            'timestamp' => '2025-07-31 19:34:00',
            'updated_by' => 'DenuJanuari'
        ]);
    })->name('debug.routes');

    Route::get('/debug/session', function () {
        return response()->json([
            'session_data' => session()->all(),
            'csrf_token' => csrf_token(),
            'timestamp' => '2025-07-31 19:34:00',
            'user' => auth()->user()->name ?? 'Guest',
            'current_login' => 'DenuJanuari'
        ]);
    })->name('debug.session');

    // Enhanced debug route for checkout data
    Route::get('/debug/checkout', function () {
        return response()->json([
            'cart_data' => session('cart_data'),
            'user' => auth()->user() ? [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email
            ] : null,
            'available_routes' => [
                'checkout.index' => route('checkout.index'),
                'user.checkout.index' => route('user.checkout.index'),
                'cart.prepare-checkout' => route('cart.prepare-checkout')
            ],
            'timestamp' => '2025-07-31 19:34:00',
            'current_login' => 'DenuJanuari'
        ]);
    })->middleware('auth')->name('debug.checkout');
}

// -----------------------------
// CUSTOM ERROR ROUTES
// -----------------------------
foreach ([401, 403, 404, 419, 422, 429, 500, 503] as $code) {
    Route::get("/error/$code", function () use ($code) {
        return response()->view("errors.$code", [
            'timestamp'     => '2025-07-31 19:34:00',
            'user'          => 'DenuJanuari',
            'current_login' => 'DenuJanuari'
        ], $code);
    })->name("error.$code");
}

// -----------------------------
// FALLBACK ROUTE (redirect to home)
// -----------------------------
Route::fallback(function () {
    \Log::info('Fallback route accessed', [
        'url'           => request()->fullUrl(),
        'method'        => request()->method(),
        'ip'            => request()->ip(),
        'user_agent'    => request()->userAgent(),
        'timestamp'     => '2025-07-31 19:34:00',
        'user'          => 'DenuJanuari',
        'current_login' => 'DenuJanuari'
    ]);

    return redirect()->route('home')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
});
