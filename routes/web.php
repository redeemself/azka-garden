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

/**
 * ==================================
 * AZKA GARDEN E-COMMERCE APPLICATION
 * Routes Configuration
 * Last updated: 2025-07-30 02:02:00
 * Author: mulyadafa
 * ==================================
 */

// -----------------------------
// GLOBAL HOME ROUTE (WAJIB ADA)
// -----------------------------
Route::get('/', [PublicController::class, 'home'])->name('home');

// -----------------------------
// PUBLIC ROUTES
// -----------------------------
Route::controller(PublicController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.submit');
    Route::get('/products', 'products')->name('products.index');
    Route::get('/products/category/{category}', 'productsByCategory')->name('products.category');
    Route::get('/services', 'services')->name('services.index');
});

// FAQ Route
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Produk detail setelah kategori agar tidak bentrok
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
Route::post('/policy/accept', function(Request $request) {
    return redirect()->back()->with('success', 'Kebijakan privasi diterima.');
})->name('policy.accept');

Route::get('/policy/reset', function() {
    return view('policies.reset_confirmation');
})->name('policy.reset.form');

Route::post('/policy/reset', function(Request $request) {
    return redirect()->route('privacy')->with('success', 'Persetujuan kebijakan privasi telah direset.');
})->name('policy.reset');

// -----------------------------
// PROMO CODE ACTIVATION & DEACTIVATION
// -----------------------------
Route::middleware(['web'])->group(function() {
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

    // Address routes (outside of user prefix for compatibility)
    Route::post('/address', [AddressController::class, 'store'])->name('address.store');
    Route::post('/address/update-coords', [AddressController::class, 'updateCoords'])->name('address.updateCoords');

    // -----------------------------
    // CHECKOUT ROUTES
    // -----------------------------
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// -----------------------------
// USER DASHBOARD ROUTES
// -----------------------------
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.profile.index'))->name('home');

    // Profile User
    Route::controller(ProfileController::class)->group(function() {
        Route::get('profile', 'index')->name('profile.index');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
        Route::post('confirm-roles', 'confirmRoles')->name('confirmRoles');
    });

    // Address Management
    Route::prefix('address')->name('address.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
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

    // Cart Routes - Improved with both POST and method spoofing support
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('add', 'add')->name('add');
        
        // Supports both direct POST and method spoofing
        Route::match(['post', 'put'], 'update/{id}', 'update')->name('update');
        Route::match(['post', 'delete'], 'delete/{id}', 'delete')->name('delete');
        
        Route::post('redeem', 'redeemPromo')->name('redeem');
        Route::post('save-shipping', 'saveShipping')->name('save-shipping');
        Route::post('save-payment', 'savePayment')->name('save-payment');
    });

    // -----------------------------
    // PAYMENT ROUTES - NEW ADDITION
    // -----------------------------
    Route::prefix('payment')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('process', 'process')->name('process');
        Route::get('success/{order}', 'success')->name('success');
        Route::get('failed/{order?}', 'failed')->name('failed');
    });

    // Orders Routes - IMPROVED: Removed confirm.blade.php routes + Added payment integration
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        // List views
        Route::get('/', 'index')->name('index');
        Route::get('history', 'history')->name('history.index');
        
        // Order CRUD operations in logical order
        Route::post('create', 'create')->name('create');
        Route::get('checkout/success/{order}', 'checkoutSuccess')->name('checkout.success');
        
        // Order detail view (removed confirm route)
        Route::get('{order}', 'show')->name('show');
        Route::post('{order}/pay', 'pay')->name('pay');
        
        // Order state change operations
        Route::match(['post', 'patch'], '{order}/cancel', 'cancel')->name('cancel');
        Route::match(['post', 'patch'], '{order}/expire', 'expire')->name('expire');
        Route::match(['post', 'patch'], '{order}/complete', 'complete')->name('complete');
        Route::post('{order}/finish', 'finish')->name('finish');
        
        // Order management operations
        Route::post('cancel-confirm', 'cancelConfirm')->name('cancelConfirm');
        Route::post('cancel-draft', 'cancelDraft')->name('cancel-draft');
        Route::post('clear_expired', 'clearExpired')->name('clear_expired');
    });

    // -----------------------------
    // ADDRESS MANAGEMENT - ENHANCED
    // -----------------------------
    Route::prefix('addresses')->name('addresses.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{address}/edit', 'edit')->name('edit');
        Route::put('{address}', 'update')->name('update');
        Route::delete('{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
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
    
    // Admin Profile
    Route::controller(AdminProfileController::class)->group(function() {
        Route::get('profile', 'index')->name('profile');
        Route::get('profile/edit', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
    });
    
    // Admin Logout
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
Route::fallback(function() {
    return redirect()->route('home')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
});