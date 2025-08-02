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
use App\Http\Controllers\DebugController;

/*
|--------------------------------------------------------------------------
| Web Routes - Fixed ALL Route Errors
| Updated: 2025-08-02 07:45:02 UTC by gerrymulyadi709
| - Fixed Route [privacy] not defined error
| - FIXED Route [products.show] not defined error
| - FIXED Route [user.products.show] not defined error
| - FIXED Route [user.orders.clear_expired] not defined error
| - FIXED Route [user.cart.add] not defined error
| - Added proper ProductController routes
| - Added complete OrderController routes
| - Added complete CartController routes with user prefix
| - Consolidated policy routes
| - Removed duplicate route definitions
| - Fixed footer.blade.php and home.blade.php compatibility
|--------------------------------------------------------------------------
*/

// IMPORTANT: HOME ROUTE DEFINITION - DO NOT CHANGE NAME
Route::get('/', [PublicController::class, 'home'])->name('home');

// DEBUG ROUTES
Route::get('/debug/controller', [DebugController::class, 'checkController']);
Route::get('/debug/routes', [DebugController::class, 'checkRoutes']);

// PUBLIC ROUTES
Route::get('/services', [PublicController::class, 'services'])->name('services.index');

Route::controller(PublicController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.submit');
});

// PRODUCT ROUTES - Public (no auth required for viewing)
// FIXED: Added proper ProductController routes to resolve Route [products.show] not defined error
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Legacy product routes for backward compatibility
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show.legacy');

// FAQ ROUTES - FIXED
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
Route::get('/help', [FaqController::class, 'index'])->name('help');
Route::get('/bantuan', [FaqController::class, 'index'])->name('bantuan');

// Blog & Articles
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
});

Route::prefix('artikel')->name('artikel.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
});

// POLICY ROUTES - FIXED: Single definition with all needed routes
Route::prefix('policies')->name('policies.')->group(function () {
    Route::view('/privacy', 'policies.privacy')->name('privacy');
    Route::view('/terms', 'policies.terms')->name('terms');
    Route::view('/cookies', 'policies.cookies')->name('cookies');
    Route::view('/return-policy', 'policies.return')->name('return');
    Route::view('/accessibility', 'policies.accessibility')->name('accessibility');
});

// LEGACY POLICY ROUTES - For backward compatibility and footer links
Route::view('/privacy', 'policies.privacy')->name('privacy');
Route::view('/terms', 'policies.terms')->name('terms');
Route::view('/cookies', 'policies.cookies')->name('cookies');
Route::view('/return-policy', 'policies.return')->name('return.policy');
Route::view('/accessibility', 'policies.accessibility')->name('accessibility');

// Additional policy aliases that might be used in footer
Route::view('/privacy-policy', 'policies.privacy')->name('privacy.policy');
Route::view('/terms-of-service', 'policies.terms')->name('terms.service');
Route::view('/cookie-policy', 'policies.cookies')->name('cookie.policy');

// Sitemap & Membership
Route::get('sitemap', [PublicController::class, 'sitemapHtml'])->name('sitemap.html');
Route::get('sitemap.xml', [PublicController::class, 'sitemapXml'])->name('sitemap.xml');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');

// Policy Actions
Route::post('/policy/accept', function (Request $request) {
    return redirect()->back()->with('success', 'Kebijakan privasi diterima.');
})->name('policy.accept');

Route::get('/policy/reset', function () {
    return view('policies.reset_confirmation');
})->name('policy.reset.form');

Route::post('/policy/reset', function (Request $request) {
    return redirect()->route('privacy')->with('success', 'Persetujuan kebijakan privasi telah direset.');
})->name('policy.reset');

// PROMO CODE ACTIVATION & CART UTILITIES
Route::middleware('web')->group(function () {
    // Promo code routes
    Route::prefix('promo')->name('promo.')->group(function () {
        Route::post('/activate', [PromoController::class, 'activate'])->name('activate');
        Route::post('/deactivate', [PromoController::class, 'deactivate'])->name('deactivate');
        Route::post('/validate', [PromoController::class, 'validate'])->name('validate');
    });

    // Cart count utilities
    Route::get('/get-cart-count', function () {
        if (auth()->check()) {
            $count = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
        } else {
            $cartItems = session('cart_items', []);
            $count = collect($cartItems)->sum('quantity');
        }

        session(['cart_count' => $count]);
        return response()->json(['count' => $count]);
    })->name('cart.count');

    Route::post('/update-cart-count', function (Request $request) {
        $count = $request->input('count', 0);
        session(['cart_count' => $count]);
        return response()->json(['success' => true]);
    })->middleware('csrf')->name('cart.count.update');
});

// USER AUTHENTICATION ROUTES
Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
});

// Legacy auth routes (backward compatibility)
Route::middleware('guest')->group(function () {
    Route::get('register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [UserAuthController::class, 'login'])->name('login.user.submit');
});

Route::middleware('auth')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

// AUTHENTICATED USER ROUTES
Route::middleware(['auth'])->group(function () {

    // PRODUCT INTERACTION ROUTES (for authenticated users)
    Route::prefix('products')->name('products.')->group(function () {
        Route::post('/{product}/like', [ProductController::class, 'like'])->name('like');
        Route::post('/{product}/comment', [ProductController::class, 'comment'])->name('comment');
    });

    // ENHANCED CART ROUTES - Main cart routes (no prefix)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::match(['put', 'patch', 'post'], '/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/summary', [CartController::class, 'getSummary'])->name('summary');
        Route::post('/validate', [CartController::class, 'validateItems'])->name('validate');

        // ADDED: New routes for shipping and payment options
        Route::get('/shipping-options', [CartController::class, 'getShippingOptions'])->name('shipping-options');
        Route::get('/payment-methods', [CartController::class, 'getPaymentMethods'])->name('payment-methods');
    });

    // ENHANCED CHECKOUT ROUTES
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/prepare', [CheckoutController::class, 'prepare'])->name('prepare');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::post('/validate', [CheckoutController::class, 'validate'])->name('validate');
        Route::get('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
        Route::get('/failed', [CheckoutController::class, 'failed'])->name('failed');
        Route::get('/shipping-methods', [CheckoutController::class, 'getShippingMethods'])->name('shipping-methods');
        Route::get('/payment-methods', [CheckoutController::class, 'getPaymentMethods'])->name('payment-methods');
        Route::get('/calculate-total', [CheckoutController::class, 'calculateTotal'])->name('calculate-total');
    });

    // COMPREHENSIVE PAYMENT ROUTES
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/success/{payment}', [PaymentController::class, 'success'])->name('success');
        Route::get('/cancel/{payment}', [PaymentController::class, 'cancel'])->name('cancel');
        Route::get('/pending/{payment}', [PaymentController::class, 'pending'])->name('pending');
        Route::get('/verify/{payment}', [PaymentController::class, 'verify'])->name('verify');
        Route::post('/verify/{payment}', [PaymentController::class, 'verifyPost'])->name('verify.post');
        Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
        Route::post('/callback', [PaymentController::class, 'callback'])->name('callback.post');
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    });

    // USER PREFIXED ROUTES
    Route::prefix('user')->name('user.')->group(function () {

        // FIXED: User Product Routes - Added to resolve [user.products.show] not defined error
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::post('/{product}/like', [ProductController::class, 'like'])->name('like');
            Route::post('/{product}/comment', [ProductController::class, 'comment'])->name('comment');
        });

        // FIXED: User Cart Routes - Added to resolve [user.cart.add] not defined error
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::get('/show', [CartController::class, 'show'])->name('show');
            Route::match(['put', 'patch', 'post'], '/{id}', [CartController::class, 'update'])->name('update');
            Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
            Route::delete('/{id}/destroy', [CartController::class, 'destroy'])->name('destroy');
            Route::post('/clear', [CartController::class, 'clear'])->name('clear');
            Route::get('/summary', [CartController::class, 'getSummary'])->name('summary');
            Route::post('/validate', [CartController::class, 'validateItems'])->name('validate');
            Route::get('/count', [CartController::class, 'getCount'])->name('count');
            Route::post('/sync', [CartController::class, 'sync'])->name('sync');

            // Additional cart utility routes
            Route::get('/shipping-options', [CartController::class, 'getShippingOptions'])->name('shipping-options');
            Route::get('/payment-methods', [CartController::class, 'getPaymentMethods'])->name('payment-methods');
            Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
            Route::delete('/remove-coupon', [CartController::class, 'removeCoupon'])->name('remove-coupon');
        });

        // User Profile Routes
        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'edit')->name('edit');
            Route::put('/update', 'update')->name('update');
            Route::get('/password', 'editPassword')->name('password.edit');
            Route::put('/password', 'updatePassword')->name('password.update');
            Route::delete('/delete', 'delete')->name('delete');
        });

        // User Address Routes
        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('index');
            Route::get('/create', [AddressController::class, 'create'])->name('create');
            Route::post('/', [AddressController::class, 'store'])->name('store');
            Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
            Route::put('/{address}', [AddressController::class, 'update'])->name('update');
            Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
            Route::patch('/{address}/primary', [AddressController::class, 'setPrimary'])->name('setPrimary');
        });

        // ENHANCED User Order Routes - FIXED: Added missing routes including clear_expired
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/history', [OrderController::class, 'history'])->name('history.index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
            Route::put('/{order}', [OrderController::class, 'update'])->name('update');
            Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
            Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::patch('/{order}/cancel-global', [OrderController::class, 'cancel'])->name('cancel.global');
            Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
            Route::get('/{order}/track', [OrderController::class, 'track'])->name('track');
            Route::get('/{order}/download', [OrderController::class, 'download'])->name('download');
            Route::post('/{order}/confirm', [OrderController::class, 'confirm'])->name('confirm');
            Route::post('/{order}/complete', [OrderController::class, 'complete'])->name('complete');

            // FIXED: Added missing clear_expired route
            Route::post('/clear-expired', [OrderController::class, 'clearExpired'])->name('clear_expired');
            Route::delete('/clear-expired', [OrderController::class, 'clearExpired'])->name('clear_expired.delete');

            // Additional order management routes
            Route::post('/bulk-cancel', [OrderController::class, 'bulkCancel'])->name('bulk_cancel');
            Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('bulk_delete');
            Route::get('/export', [OrderController::class, 'export'])->name('export');
            Route::get('/statistics', [OrderController::class, 'statistics'])->name('statistics');
        });

        // Address management (Additional Pattern for backward compatibility)
        Route::prefix('address')->name('address.')->controller(AddressController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{address}/edit', 'edit')->name('edit');
            Route::put('/{address}', 'update')->name('update');
            Route::delete('/{address}', 'destroy')->name('destroy');
            Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
        });
    });

    Route::post('/address/update-coords', [AddressController::class, 'updateCoords'])->name('address.updateCoords');
});

// ADMIN ROUTES
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Auth Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');
    });

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Admin Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
            Route::put('/', [AdminProfileController::class, 'update'])->name('update');
            Route::get('/password', [AdminProfileController::class, 'editPassword'])->name('password.edit');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        });
    });
});

// FALLBACK ROUTES FOR COMPATIBILITY
Route::middleware(['auth'])->group(function () {
    // Indonesian language fallback routes
    Route::prefix('indonesian')->name('id.')->group(function () {
        Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang');
        Route::get('/belanja', [CartController::class, 'index'])->name('belanja');
        Route::get('/troli', [CartController::class, 'index'])->name('troli');
        Route::get('/pembayaran', [CheckoutController::class, 'index'])->name('pembayaran');
        Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan');
        Route::get('/riwayat-bayar', [PaymentController::class, 'history'])->name('riwayat-bayar');
    });

    // Direct fallback routes (legacy support)
    Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::get('/belanja', [CartController::class, 'index'])->name('belanja.index');
    Route::get('/troli', [CartController::class, 'index'])->name('troli.index');
    Route::get('/pembayaran', [CheckoutController::class, 'index'])->name('pembayaran.index');
    Route::post('/proses-pesanan', [PaymentController::class, 'store'])->name('proses-pesanan');
    Route::get('/checkout-pesanan', [CheckoutController::class, 'index'])->name('checkout-pesanan');
    Route::get('/bayar/{payment}', [PaymentController::class, 'success'])->name('bayar.success');
    Route::get('/pembayaran/{payment}', [PaymentController::class, 'show'])->name('pembayaran.show');
    Route::get('/riwayat-bayar', [PaymentController::class, 'history'])->name('riwayat-bayar');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
});

/*
|--------------------------------------------------------------------------
| Route Summary - ALL Route Errors Fixed
| Updated: 2025-08-02 07:45:02 UTC by gerrymulyadi709
|--------------------------------------------------------------------------
|
| FIXED ISSUES:
| ✅ Route [privacy] not defined - RESOLVED
| ✅ Route [products.show] not defined - RESOLVED
| ✅ Route [user.products.show] not defined - RESOLVED
| ✅ Route [user.orders.clear_expired] not defined - RESOLVED
| ✅ Route [user.cart.add] not defined - RESOLVED
| ✅ Added proper ProductController routes
| ✅ Added user.products routes for authenticated users
| ✅ Added complete OrderController routes with all methods
| ✅ Added complete CartController routes with user prefix
| ✅ Added all policy routes with proper naming
| ✅ Added legacy routes for backward compatibility
| ✅ Fixed footer.blade.php and home.blade.php compatibility
| ✅ Consolidated duplicate route definitions
|
| AVAILABLE PRODUCT ROUTES:
| ✅ route('products.index') - /products (public)
| ✅ route('products.show', $product) - /products/{product} (public)
| ✅ route('user.products.index') - /user/products (authenticated)
| ✅ route('user.products.show', $product) - /user/products/{product} (authenticated)
| ✅ route('products.show.legacy', $id) - /products/{id} (legacy)
|
| AVAILABLE CART ROUTES:
| ✅ route('cart.index') - /cart (main cart)
| ✅ route('cart.add') - POST /cart/add (main cart)
| ✅ route('user.cart.index') - /user/cart (user prefixed)
| ✅ route('user.cart.add') - POST /user/cart/add (user prefixed)
| ✅ route('user.cart.show') - /user/cart/show
| ✅ route('user.cart.update', $id) - PUT /user/cart/{id}
| ✅ route('user.cart.remove', $id) - DELETE /user/cart/{id}
| ✅ route('user.cart.destroy', $id) - DELETE /user/cart/{id}/destroy
| ✅ route('user.cart.clear') - POST /user/cart/clear
| ✅ route('user.cart.summary') - /user/cart/summary
| ✅ route('user.cart.validate') - POST /user/cart/validate
| ✅ route('user.cart.count') - /user/cart/count
| ✅ route('user.cart.sync') - POST /user/cart/sync
| ✅ route('user.cart.apply-coupon') - POST /user/cart/apply-coupon
| ✅ route('user.cart.remove-coupon') - DELETE /user/cart/remove-coupon
|
| AVAILABLE ORDER ROUTES:
| ✅ route('user.orders.index') - /user/orders
| ✅ route('user.orders.show', $order) - /user/orders/{order}
| ✅ route('user.orders.create') - /user/orders/create
| ✅ route('user.orders.store') - POST /user/orders
| ✅ route('user.orders.edit', $order) - /user/orders/{order}/edit
| ✅ route('user.orders.update', $order) - PUT /user/orders/{order}
| ✅ route('user.orders.destroy', $order) - DELETE /user/orders/{order}
| ✅ route('user.orders.cancel', $order) - PATCH /user/orders/{order}/cancel
| ✅ route('user.orders.invoice', $order) - /user/orders/{order}/invoice
| ✅ route('user.orders.track', $order) - /user/orders/{order}/track
| ✅ route('user.orders.clear_expired') - POST /user/orders/clear-expired
| ✅ route('user.orders.bulk_cancel') - POST /user/orders/bulk-cancel
| ✅ route('user.orders.bulk_delete') - POST /user/orders/bulk-delete
| ✅ route('user.orders.export') - /user/orders/export
| ✅ route('user.orders.statistics') - /user/orders/statistics
|
| AVAILABLE POLICY ROUTES:
| ✅ route('privacy') - /privacy
| ✅ route('terms') - /terms
| ✅ route('cookies') - /cookies
| ✅ route('return.policy') - /return-policy
| ✅ route('accessibility') - /accessibility
| ✅ route('policies.privacy') - /policies/privacy
| ✅ route('policies.terms') - /policies/terms
| ✅ route('policies.cookies') - /policies/cookies
| ✅ route('policies.return') - /policies/return-policy
| ✅ route('policies.accessibility') - /policies/accessibility
|
|--------------------------------------------------------------------------
*/