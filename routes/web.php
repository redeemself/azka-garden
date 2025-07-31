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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\User\PromoController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\ServicesController;

/**
 * ==================================
 * AZKA GARDEN E-COMMERCE APPLICATION
 * Routes Configuration
 * Last updated: 2025-07-31 06:36:01
 * Author: mulyadafa, marseltriwanto
 * ==================================
 */

// -----------------------------
// GLOBAL HOME ROUTE
// -----------------------------
Route::get('/', [PublicController::class, 'home'])->name('home');

// -----------------------------
// SERVICES ROUTE
// -----------------------------
Route::get('/services', [PublicController::class, 'services'])->name('services.index');

// -----------------------------
// CART ROUTES - WITH MULTIPLE NAMING PATTERNS FOR COMPATIBILITY
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Main cart page with BOTH route names for compatibility
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/user/cart', [CartController::class, 'index'])->name('user.cart.index');

    // Cart operations - With BOTH naming patterns for backward compatibility
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updatePost'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-promo', [CartController::class, 'redeemPromo'])->name('cart.apply-promo');
    Route::post('/cart/update-shipping', [CartController::class, 'saveShipping'])->name('cart.update-shipping');

    // Add user.cart routes for backward compatibility
    Route::post('/user/cart/add', [CartController::class, 'add'])->name('user.cart.add');
    Route::post('/user/cart/update/{id}', [CartController::class, 'updatePost'])->name('user.cart.update');
    Route::post('/user/cart/remove/{id}', [CartController::class, 'remove'])->name('user.cart.remove');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/confirm', [CartController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// -----------------------------
// PUBLIC ROUTES
// -----------------------------
Route::controller(PublicController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.submit');

    // Products routes
    Route::get('/products', 'products')->name('products');
});

// Add this as a separate route to avoid conflicts
Route::get('/products', [PublicController::class, 'products'])->name('products.index');

// FAQ Route
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Produk detail
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// -----------------------------
// BLOG & ARTICLE ROUTES
// -----------------------------
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

Route::prefix('artikel')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('artikel.index');
});

// -----------------------------
// SITEMAP ROUTES
// -----------------------------
Route::get('sitemap', [PublicController::class, 'sitemapHtml'])->name('sitemap.html');
Route::get('sitemap.xml', [PublicController::class, 'sitemapXml'])->name('sitemap.xml');

// -----------------------------
// NEWSLETTER & MEMBERSHIP ROUTES
// -----------------------------
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');

// -----------------------------
// POLICY PAGES
// -----------------------------
Route::view('/privacy', 'policies.privacy')->name('privacy');
Route::view('/terms', 'policies.terms')->name('terms');
Route::view('/cookies', 'policies.cookies')->name('cookies');
Route::view('/return-policy', 'policies.return')->name('return.policy');
Route::view('/accessibility', 'policies.accessibility')->name('accessibility');

// -----------------------------
// POLICY ACCEPT & RESET
// -----------------------------
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
// PROMO CODE ACTIVATION & DEACTIVATION
// -----------------------------
Route::middleware(['web'])->group(function () {
    Route::post('/promo/activate', [PromoController::class, 'activate'])->name('promo.activate');
    Route::post('/promo/deactivate', [PromoController::class, 'deactivate'])->name('promo.deactivate');
});

// -----------------------------
// CART & CHECKOUT ROUTES (WEB middleware)
// -----------------------------
Route::middleware(['web'])->group(function () {
    Route::get('/cart',           [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{id}',    [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}',   [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/cart/promo',    [CartController::class, 'applyPromo'])->name('cart.applyPromo');
    Route::delete('/cart/promo',  [CartController::class, 'removePromo'])->name('cart.removePromo');

    Route::post('/cart/shipping', [CartController::class, 'selectShipping'])->name('cart.selectShipping');

    Route::get('/checkout',       [CheckoutController::class, 'index'])->name('checkout.index');
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

// -----------------------------
// USER LOGOUT ROUTE
// -----------------------------
Route::middleware('auth')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

// -----------------------------
// AUTHENTICATED USER ROUTES
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Like & Comment Product Routes
    Route::post('/products/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/products/{id}/comment', [ProductController::class, 'comment'])->name('products.comment');

    // Address routes
    Route::prefix('user/address')->name('user.address.')->controller(AddressController::class)->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{address}/edit', 'edit')->name('edit');
        Route::put('/{address}', 'update')->name('update');
        Route::delete('/{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });

    Route::post('/address/update-coords', [AddressController::class, 'updateCoords'])->name('address.updateCoords');

    // PATCH Routes for order actions (global access)
    Route::patch('user/orders/{order}/expire', [OrderController::class, 'expire'])->name('user.orders.expire.global');
    Route::patch('user/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel.global');
    Route::patch('user/orders/{order}/complete', [OrderController::class, 'complete'])->name('user.orders.complete.global');
});

// -----------------------------
// USER DASHBOARD ROUTES
// -----------------------------
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.profile.index'))->name('home');

    // Profile User
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
        Route::post('confirm-roles', 'confirmRoles')->name('confirmRoles');
    });

    // Address Management
    Route::prefix('addresses')->name('addresses.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{address}/edit', 'edit')->name('edit');
        Route::put('{address}', 'update')->name('update');
        Route::delete('{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });

    // Produk khusus user login
    Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('redeem', 'redeemForm')->name('redeem.form');
        Route::post('redeem', 'redeemPromo')->name('redeem');
        Route::get('{id}', 'show')->name('show');
    });

    // Payment Routes
    Route::prefix('payment')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('process', 'process')->name('process');
        Route::get('success/{order}', 'success')->name('success');
        Route::get('failed/{order?}', 'failed')->name('failed');
    });

    // Orders Routes
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
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

// -----------------------------
// ADMIN DASHBOARD & PROFILE
// -----------------------------
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
// CUSTOM ERROR ROUTES
// -----------------------------
foreach ([401, 403, 404, 419, 422, 429, 500, 503] as $code) {
    Route::get("/error/$code", function () use ($code) {
        return response()->view("errors.$code", [], $code);
    })->name("error.$code");
}

// -----------------------------
// FALLBACK ROUTE (redirect to home)
// -----------------------------
Route::fallback(function () {
    return redirect()->route('home')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
});
