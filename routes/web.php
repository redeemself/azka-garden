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
use App\Http\Controllers\DevController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\User\PromoController;
use App\Http\Controllers\User\AddressController;

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

// Produk detail setelah kategori agar tidak bentrok
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// -----------------------------
// LIKE & COMMENT PRODUCT ROUTES (auth middleware)
// -----------------------------
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/products/{id}/comment', [ProductController::class, 'comment'])->name('products.comment');

    // Address routes
    Route::post('/user/address', [AddressController::class, 'store'])->name('user.address.store');
    Route::post('/user/address/update-coords', [AddressController::class, 'updateCoords'])->name('user.address.updateCoords');
});

// -----------------------------
// FAQ ROUTE
// -----------------------------
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// -----------------------------
// SITEMAP ROUTES
// -----------------------------
Route::get('sitemap', [PublicController::class, 'sitemapHtml'])->name('sitemap.html');
Route::get('sitemap.xml', [PublicController::class, 'sitemapXml'])->name('sitemap.xml');

// -----------------------------
// BLOG ROUTES
// -----------------------------
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');

// -----------------------------
// NEWSLETTER ROUTES
// -----------------------------
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// -----------------------------
// MEMBERSHIP ROUTE
// -----------------------------
Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');

// -----------------------------
// POLICY PAGES
// -----------------------------
Route::view('/privacy', 'policies.privacy')->name('privacy');
Route::view('/terms', 'policies.terms')->name('terms');
Route::view('/cookies', 'policies.cookies')->name('cookies');
Route::view('/return-policy', 'policies.return')->name('return.policy');
Route::view('/accessibility', 'policies.accessibility')->name('accessibility');

// ---------------
// POLICY ACCEPT & RESET
// ---------------
Route::post('/policy/accept', function(Request $request) {
    return redirect()->back()->with('success', 'Kebijakan privasi diterima.');
})->name('policy.accept');

Route::post('/policy/reset', function(Request $request) {
    return redirect()->route('privacy')->with('success', 'Persetujuan kebijakan privasi telah direset.');
})->name('policy.reset');

Route::get('/policy/reset', function() {
    return view('policies.reset_confirmation');
})->name('policy.reset.form');

// -----------------------------
// ARTICLE ROUTES
// -----------------------------
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('artikel.index');
});

// -----------------------------
// USER AUTH (guest middleware)
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [UserAuthController::class, 'login'])->name('login.user.submit');
});

// -----------------------------
// USER LOGOUT (auth:web middleware)
// -----------------------------
Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');

// -----------------------------
// USER DASHBOARD (prefix 'user', auth:web middleware)
// -----------------------------
Route::middleware('auth:web')->prefix('user')->name('user.')->group(function () {
    Route::get('/', fn() => redirect()->route('user.profile.index'))->name('home');

    // Profile User
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('confirm-roles', [ProfileController::class, 'confirmRoles'])->name('confirmRoles');

    // Produk khusus user login
    Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('redeem', 'redeemForm')->name('redeem.form');
        Route::post('redeem', 'redeemPromo')->name('redeem');
        Route::get('{id}', 'show')->name('show');
    });

    // Keranjang User (cart) — pakai POST untuk update & delete supaya AJAX lancar
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('add', 'add')->name('add');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('delete/{id}', 'delete')->name('delete');
        // Perbaikan: redeem promo menggunakan route POST 'cart/redeem'
        Route::post('redeem', 'redeemPromo')->name('redeem');
        Route::post('save-shipping', 'saveShipping')->name('save-shipping');
        Route::post('save-payment', 'savePayment')->name('save-payment');
        
        // PERBAIKAN: Tambahkan dukungan untuk method spoofing PUT/DELETE
        Route::put('update/{id}', 'update')->name('update.put');
        Route::delete('delete/{id}', 'delete')->name('delete.delete');
    });

    // Orders User
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('history', 'history')->name('history.index');
        Route::get('{order}', 'show')->name('show');
        Route::post('create', 'create')->name('create');
        Route::post('{order}/cancel', 'cancel')->name('cancel');
        Route::patch('{order}/cancel', 'cancel')->name('cancelPatch');
        Route::get('{order}/confirm', 'confirm')->name('confirm');
        Route::post('{order}/pay', 'pay')->name('pay');
        Route::post('cancel-confirm', 'cancelConfirm')->name('cancelConfirm');
        Route::post('clear_expired', 'clearExpired')->name('clear_expired');
        Route::post('{order}/finish', 'finish')->name('finish');
        Route::post('cancel-draft', 'cancelDraft')->name('cancel-draft');
        Route::patch('{order}/expire', 'expire')->name('expire');
        Route::patch('{order}/complete', 'complete')->name('complete');
    });

    // Address group for user
    Route::prefix('address')->name('address.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{address}', 'update')->name('update');
        Route::delete('{address}', 'destroy')->name('destroy');
        Route::patch('{address}/primary', 'setPrimary')->name('setPrimary');
    });
});

// -----------------------------
// CART FALLBACK ROUTES FOR METHOD SPOOFING COMPATIBILITY
// -----------------------------
Route::middleware('auth:web')->group(function() {
    // These routes help with AJAX requests that try to use PUT/DELETE directly
    Route::post('/user/cart/update/{id}', [CartController::class, 'update'])->name('user.cart.update.fallback');
    Route::post('/user/cart/delete/{id}', [CartController::class, 'delete'])->name('user.cart.delete.fallback');
    
    // Direct fallbacks without prefix for browser compatibility
    Route::post('cart/update/{id}', [CartController::class, 'update']);
    Route::post('cart/delete/{id}', [CartController::class, 'delete']);
});

// -----------------------------
// PATCH ROUTES AGAR BISA DIAKSES DARI NAMA DI BLADE (akses global, tidak dalam prefix user)
// -----------------------------
Route::middleware(['auth'])->group(function() {
    Route::patch('user/orders/{order}/expire', [OrderController::class, 'expire'])->name('user.orders.expire');
    Route::patch('user/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::patch('user/orders/{order}/complete', [OrderController::class, 'complete'])->name('user.orders.complete');
});

// -----------------------------
// PROMO CODE ACTIVATION & DEACTIVATION (untuk semua user)
// -----------------------------
Route::post('/promo/activate', [PromoController::class, 'activate'])->name('promo.activate');
Route::post('/promo/deactivate', [PromoController::class, 'deactivate'])->name('promo.deactivate');

// -----------------------------
// ADMIN AUTH
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
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::get('profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// -----------------------------
// DEVELOPER AUTH
// -----------------------------
Route::prefix('dev')->name('dev.')->middleware('guest:developer')->group(function () {
    Route::get('register', [DevController::class, 'showRegister'])->name('register');
    Route::post('register', [DevController::class, 'register'])->name('register.submit');
    Route::get('login', [DevController::class, 'showLogin'])->name('login');
    Route::post('login', [DevController::class, 'login'])->name('login.submit');
});

// -----------------------------
// DEVELOPER DASHBOARD
// -----------------------------
Route::prefix('dev')->name('dev.')->middleware(['auth:developer', 'developer'])->group(function () {
    Route::get('/', [DevController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [DevController::class, 'profile'])->name('profile');
    Route::put('profile', [DevController::class, 'updateProfile'])->name('profile.update');
    Route::post('logout', [DevController::class, 'logout'])->name('logout');
});

// -----------------------------
// CUSTOM ERROR ROUTES (401-503)
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
    return redirect()->route('home');
});