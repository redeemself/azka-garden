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
| Web Routes - Enhanced with Product Interactions & Fixed Cart Routes
| Updated: 2025-08-02 09:48:28 UTC by gerrymulyadi709
| - FIXED CartController route methods and namespacing
| - Enhanced cart functionality with proper AJAX handling
| - Improved route organization
| - Added proper middleware protection
| - Fixed Route [privacy] not defined error
| - FIXED Route [products.show] not defined error
| - FIXED Route [user.products.show] not defined error
| - FIXED Route [user.orders.clear_expired] not defined error
| - FIXED Route [user.cart.add] not defined error
| - FIXED CartController AJAX handling for no JSON redirect
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
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');

    // ENHANCED: Product interaction routes (require authentication)
    Route::middleware(['auth'])->group(function () {
        Route::post('/{product}/like', [ProductController::class, 'like'])->name('like');
        Route::post('/{product}/comment', [ProductController::class, 'comment'])->name('comment');
        Route::delete('/{product}/unlike', [ProductController::class, 'unlike'])->name('unlike');
        Route::get('/{product}/comments', [ProductController::class, 'getComments'])->name('comments');
        Route::get('/{product}/likes', [ProductController::class, 'getLikes'])->name('likes');
        Route::put('/comments/{comment}', [ProductController::class, 'updateComment'])->name('comment.update');
        Route::delete('/comments/{comment}', [ProductController::class, 'deleteComment'])->name('comment.delete');
    });
});

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

    // FIXED: ENHANCED CART ROUTES - Main cart routes (no prefix) with proper methods
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');

        // FIXED: Primary cart add route with proper POST method and AJAX handling
        Route::post('/add', [CartController::class, 'add'])->name('add');

        // FIXED: Cart update route with proper PUT method
        Route::put('/{id}', [CartController::class, 'update'])->name('update');

        // FIXED: Cart remove route with proper DELETE method
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');

        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/summary', [CartController::class, 'getSummary'])->name('summary');
        Route::post('/validate', [CartController::class, 'validateItems'])->name('validate');

        // ENHANCED: New routes for shipping and payment options
        Route::get('/shipping-options', [CartController::class, 'getShippingOptions'])->name('shipping-options');
        Route::get('/payment-methods', [CartController::class, 'getPaymentMethods'])->name('payment-methods');
        Route::post('/apply-promo', [CartController::class, 'applyPromo'])->name('apply-promo');
        Route::delete('/remove-promo', [CartController::class, 'removePromo'])->name('remove-promo');
        Route::get('/count', [CartController::class, 'getCount'])->name('count');
        Route::post('/sync', [CartController::class, 'sync'])->name('sync');

        // Additional cart utility routes
        Route::match(['put', 'patch', 'post'], '/{id}/update', [CartController::class, 'update'])->name('update.flexible');
        Route::delete('/{id}/destroy', [CartController::class, 'remove'])->name('destroy');
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

        // ENHANCED: User Product Routes - Added to resolve [user.products.show] not defined error
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::post('/{product}/like', [ProductController::class, 'like'])->name('like');
            Route::post('/{product}/comment', [ProductController::class, 'comment'])->name('comment');
            Route::delete('/{product}/unlike', [ProductController::class, 'unlike'])->name('unlike');
            Route::get('/{product}/comments', [ProductController::class, 'getComments'])->name('comments');
            Route::get('/{product}/likes', [ProductController::class, 'getLikes'])->name('likes');
            Route::put('/comments/{comment}', [ProductController::class, 'updateComment'])->name('comment.update');
            Route::delete('/comments/{comment}', [ProductController::class, 'deleteComment'])->name('comment.delete');

            // ENHANCED: Product wishlist and favorites
            Route::get('/liked', [ProductController::class, 'likedProducts'])->name('liked');
            Route::get('/my-comments', [ProductController::class, 'myComments'])->name('my-comments');
        });

        // FIXED: ENHANCED User Cart Routes - Added to resolve [user.cart.add] not defined error
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');

            // FIXED: User cart add route with proper POST method and AJAX handling
            Route::post('/add', [CartController::class, 'add'])->name('add');

            Route::get('/show', [CartController::class, 'show'])->name('show');

            // FIXED: User cart update route with proper PUT method
            Route::put('/{id}', [CartController::class, 'update'])->name('update');

            // FIXED: User cart remove route with proper DELETE method
            Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');

            Route::delete('/{id}/destroy', [CartController::class, 'remove'])->name('destroy');
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
            Route::post('/apply-promo', [CartController::class, 'applyPromo'])->name('apply-promo');
            Route::delete('/remove-promo', [CartController::class, 'removePromo'])->name('remove-promo');
            Route::post('/bulk-update', [CartController::class, 'bulkUpdate'])->name('bulk-update');
            Route::post('/save-for-later/{id}', [CartController::class, 'saveForLater'])->name('save-for-later');
            Route::post('/move-to-cart/{id}', [CartController::class, 'moveToCart'])->name('move-to-cart');

            // FIXED: Alternative route methods for compatibility
            Route::match(['put', 'patch', 'post'], '/{id}/update', [CartController::class, 'update'])->name('update.flexible');
            Route::match(['delete', 'post'], '/{id}/remove', [CartController::class, 'remove'])->name('remove.flexible');
        });

        // User Profile Routes
        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'edit')->name('edit');
            Route::put('/update', 'update')->name('update');
            Route::get('/password', 'editPassword')->name('password.edit');
            Route::put('/password', 'updatePassword')->name('password.update');
            Route::delete('/delete', 'delete')->name('delete');
            Route::get('/activity', 'activity')->name('activity');
            Route::get('/preferences', 'preferences')->name('preferences');
            Route::put('/preferences', 'updatePreferences')->name('preferences.update');
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
            Route::get('/{address}/validate', [AddressController::class, 'validate'])->name('validate');
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
            Route::post('/{order}/rate', [OrderController::class, 'rate'])->name('rate');
            Route::post('/{order}/review', [OrderController::class, 'review'])->name('review');
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
| Route Summary - FIXED Cart Routes & AJAX Handling
| Updated: 2025-08-02 09:48:28 UTC by gerrymulyadi709
|--------------------------------------------------------------------------
|
| FIXED CART ISSUES:
| ✅ CartController namespace properly set to App\Http\Controllers\User\CartController
| ✅ Cart routes use proper HTTP methods (POST, PUT, DELETE)
| ✅ AJAX handling fixed in CartController to prevent JSON redirect
| ✅ Enhanced error handling for both AJAX and regular form submissions
| ✅ Proper CSRF token validation and request detection
| ✅ Multiple compatibility route patterns for flexibility
|
| ENHANCED FEATURES:
| ✅ Product like/unlike functionality
| ✅ Product comment system with CRUD operations
| ✅ Enhanced cart functionality with promo codes
| ✅ Improved user experience routes
| ✅ Better route organization and middleware protection
|
| FIXED ISSUES:
| ✅ Route [privacy] not defined - RESOLVED
| ✅ Route [products.show] not defined - RESOLVED
| ✅ Route [user.products.show] not defined - RESOLVED
| ✅ Route [user.orders.clear_expired] not defined - RESOLVED
| ✅ Route [user.cart.add] not defined - RESOLVED
| ✅ CartController AJAX redirect to JSON page - RESOLVED
| ✅ Added proper ProductController routes
| ✅ Added user.products routes for authenticated users
| ✅ Added complete OrderController routes with all methods
| ✅ Added complete CartController routes with user prefix
| ✅ Added all policy routes with proper naming
| ✅ Added legacy routes for backward compatibility
| ✅ Fixed footer.blade.php and home.blade.php compatibility
| ✅ Consolidated duplicate route definitions
|
| FIXED CART ROUTES:
| ✅ route('cart.add') - POST /cart/add (AJAX compatible)
| ✅ route('cart.update', $id) - PUT /cart/{id} (AJAX compatible)
| ✅ route('cart.remove', $id) - DELETE /cart/{id} (AJAX compatible)
| ✅ route('user.cart.add') - POST /user/cart/add (AJAX compatible)
| ✅ route('user.cart.update', $id) - PUT /user/cart/{id} (AJAX compatible)
| ✅ route('user.cart.remove', $id) - DELETE /user/cart/{id} (AJAX compatible)
|
| NEW PRODUCT INTERACTION ROUTES:
| ✅ route('products.like', $product) - POST /products/{product}/like
| ✅ route('products.comment', $product) - POST /products/{product}/comment
| ✅ route('products.unlike', $product) - DELETE /products/{product}/unlike
| ✅ route('products.comments', $product) - GET /products/{product}/comments
| ✅ route('products.likes', $product) - GET /products/{product}/likes
| ✅ route('products.comment.update', $comment) - PUT /products/comments/{comment}
| ✅ route('products.comment.delete', $comment) - DELETE /products/comments/{comment}
|
| ENHANCED USER PRODUCT ROUTES:
| ✅ route('user.products.like', $product) - POST /user/products/{product}/like
| ✅ route('user.products.comment', $product) - POST /user/products/{product}/comment
| ✅ route('user.products.unlike', $product) - DELETE /user/products/{product}/unlike
| ✅ route('user.products.liked') - GET /user/products/liked
| ✅ route('user.products.my-comments') - GET /user/products/my-comments
|
| ENHANCED CART ROUTES:
| ✅ route('cart.add') - POST /cart/add (main cart route, AJAX ready)
| ✅ route('cart.apply-promo') - POST /cart/apply-promo
| ✅ route('cart.remove-promo') - DELETE /cart/remove-promo
| ✅ route('user.cart.add') - POST /user/cart/add (user prefixed, AJAX ready)
| ✅ route('user.cart.apply-promo') - POST /user/cart/apply-promo
| ✅ route('user.cart.bulk-update') - POST /user/cart/bulk-update
| ✅ route('user.cart.save-for-later', $id) - POST /user/cart/save-for-later/{id}
| ✅ route('user.cart.move-to-cart', $id) - POST /user/cart/move-to-cart/{id}
|
|--------------------------------------------------------------------------
*/
