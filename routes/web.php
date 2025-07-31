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

/**
 * ==================================
 * AZKA GARDEN E-COMMERCE APPLICATION
 * Routes Configuration
 * Last updated: 2025-07-31 15:23:41
 * Updated by: redeemself
 * Current login: redeemself
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
// PUBLIC ROUTES
// -----------------------------
Route::controller(PublicController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->name('contact.submit');
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

// -----------------------------
// USER LOGOUT ROUTE
// -----------------------------
Route::middleware('auth')->post('logout', [UserAuthController::class, 'logout'])->name('logout');

// -----------------------------
// CART & CHECKOUT ROUTES - ENHANCED AND CONSOLIDATED
// Updated: 2025-07-31 15:23:41 by redeemself
// Current login: redeemself
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Main cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/user/cart', [CartController::class, 'index'])->name('user.cart.index'); // Backward compatibility

    // Cart operations - Standard REST patterns
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/update/{id}', [CartController::class, 'updatePost'])->name('cart.update.post'); // Backward compatibility
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Promo operations
    Route::post('/cart/apply-promo', [CartController::class, 'applyPromo'])->name('cart.apply-promo');
    Route::post('/cart/redeem-promo', [CartController::class, 'redeemPromo'])->name('cart.redeem-promo'); // Alternative name
    Route::delete('/cart/remove-promo', [CartController::class, 'removePromo'])->name('cart.remove-promo');

    // Shipping operations
    Route::post('/cart/select-shipping', [CartController::class, 'selectShipping'])->name('cart.select-shipping');
    Route::post('/cart/save-shipping', [CartController::class, 'saveShipping'])->name('cart.save-shipping'); // Alternative name
    Route::post('/cart/update-shipping', [CartController::class, 'saveShipping'])->name('cart.update-shipping'); // Backward compatibility

    // CHECKOUT PROCESS - MAIN ROUTES
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout'); // Process checkout from cart
    Route::get('/cart/proceed-checkout', [CartController::class, 'proceedToCheckout'])->name('cart.proceed-checkout'); // Alternative GET route

    // Checkout page routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Additional cart routes for backward compatibility
    Route::post('/user/cart/add', [CartController::class, 'add'])->name('user.cart.add');
    Route::post('/user/cart/update/{id}', [CartController::class, 'updatePost'])->name('user.cart.update');
    Route::post('/user/cart/remove/{id}', [CartController::class, 'remove'])->name('user.cart.remove');
});

// -----------------------------
// AUTHENTICATED USER ROUTES
// -----------------------------
Route::middleware(['auth'])->group(function () {
    // Like & Comment Product Routes
    Route::post('/products/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/products/{id}/comment', [ProductController::class, 'comment'])->name('products.comment');

    // Address routes
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

    // Payment Routes - UPDATED: 2025-07-31 15:23:41 by redeemself
    Route::prefix('payment')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::get('/', 'index')->name('index'); // Main payment page
        Route::post('/', 'store')->name('store'); // Create order and process payment
        Route::post('process', 'process')->name('process'); // Process payment
        Route::get('success/{order}', 'success')->name('success');
        Route::get('failed/{order?}', 'failed')->name('failed');
        Route::post('webhook', 'webhook')->name('webhook'); // Payment gateway webhook
    });

    // Orders Routes
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('history', 'history')->name('history.index');
        Route::post('create', 'create')->name('create'); // Create order from checkout
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
// DEBUG ROUTES (for development only)
// ENHANCED & FIXED: 2025-07-31 15:23:41 by redeemself
// Current login: redeemself
// -----------------------------
if (config('app.debug')) {
    // Application Status & Info
    Route::get('/debug/test', function () {
        return response()->json([
            'status' => 'OK',
            'message' => 'Azka Garden E-Commerce application is working correctly',
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => app()->environment(),
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ]);
    })->name('debug.test');

    // Session Information
    Route::get('/debug/session', function () {
        return response()->json([
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'session_data' => session()->all(),
            'auth_status' => auth()->check(),
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? null,
            'session_lifetime' => config('session.lifetime'),
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself'
        ]);
    })->name('debug.session');

    // Session Testing Route - ENHANCED
    Route::get('/debug/session-test', function () {
        try {
            // Test session operations with enhanced data
            $testKey = 'test_key_' . time();
            $testValue = 'test_value_' . time() . '_redeemself';

            session()->put($testKey, $testValue);
            session()->put('debug_test_data', [
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself',
                'test_id' => uniqid(),
                'app_name' => config('app.name')
            ]);

            $retrievedValue = session()->get($testKey);
            $debugData = session()->get('debug_test_data');

            return response()->json([
                'session_id' => session()->getId(),
                'session_driver' => config('session.driver'),
                'session_table' => config('session.table'),
                'session_lifetime' => config('session.lifetime'),
                'test_session_write' => $retrievedValue,
                'test_debug_data' => $debugData,
                'session_test_passed' => $testValue === $retrievedValue,
                'all_session_data' => session()->all(),
                'csrf_token' => csrf_token(),
                'session_config' => [
                    'encrypt' => config('session.encrypt'),
                    'cookie' => config('session.cookie'),
                    'domain' => config('session.domain'),
                    'secure' => config('session.secure'),
                    'http_only' => config('session.http_only'),
                    'same_site' => config('session.same_site'),
                ],
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself',
                'status' => 'Session working correctly'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'error_details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ],
                'session_driver' => config('session.driver'),
                'session_table' => config('session.table'),
                'session_config' => [
                    'driver' => config('session.driver'),
                    'connection' => config('session.connection'),
                    'table' => config('session.table'),
                ],
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself',
                'status' => 'Session error'
            ], 500);
        }
    })->name('debug.session.test');

    // Middleware Stack Information - FIXED deprecated method
    Route::get('/debug/middleware', function () {
        $kernel = app(\App\Http\Kernel::class);

        // Get middleware information using reflection to avoid deprecated methods
        $middlewareInfo = [];

        try {
            // Use reflection to access protected properties
            $reflection = new \ReflectionClass($kernel);

            // Get global middleware
            if ($reflection->hasProperty('middleware')) {
                $middlewareProperty = $reflection->getProperty('middleware');
                $middlewareProperty->setAccessible(true);
                $middlewareInfo['global_middleware'] = $middlewareProperty->getValue($kernel);
            }

            // Get middleware groups
            if ($reflection->hasProperty('middlewareGroups')) {
                $middlewareGroupsProperty = $reflection->getProperty('middlewareGroups');
                $middlewareGroupsProperty->setAccessible(true);
                $middlewareInfo['middleware_groups'] = $middlewareGroupsProperty->getValue($kernel);
            }

            // Get middleware aliases (newer Laravel versions)
            if ($reflection->hasProperty('middlewareAliases')) {
                $middlewareAliasesProperty = $reflection->getProperty('middlewareAliases');
                $middlewareAliasesProperty->setAccessible(true);
                $middlewareInfo['middleware_aliases'] = $middlewareAliasesProperty->getValue($kernel);
            }

            // Fallback for older Laravel versions with routeMiddleware
            if ($reflection->hasProperty('routeMiddleware') && !isset($middlewareInfo['middleware_aliases'])) {
                $routeMiddlewareProperty = $reflection->getProperty('routeMiddleware');
                $routeMiddlewareProperty->setAccessible(true);
                $middlewareInfo['route_middleware'] = $routeMiddlewareProperty->getValue($kernel);
            }
        } catch (\Exception $e) {
            $middlewareInfo['error'] = 'Could not access middleware information: ' . $e->getMessage();
        }

        // Current route middleware
        $middlewareInfo['current_request_middleware'] = request()->route() ? request()->route()->gatherMiddleware() : [];

        return response()->json([
            'middleware_info' => $middlewareInfo,
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself'
        ]);
    })->name('debug.middleware');

    // Routes Information
    Route::get('/debug/routes', function () {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
            ];
        });

        return response()->json([
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself',
            'total_routes' => $routes->count(),
            'routes' => $routes->toArray()
        ]);
    })->name('debug.routes');

    // Database Information
    Route::get('/debug/database', function () {
        try {
            $connection = \DB::connection();
            $pdo = $connection->getPdo();

            return response()->json([
                'connection_name' => $connection->getName(),
                'database_name' => $connection->getDatabaseName(),
                'driver_name' => $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME),
                'server_version' => $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION),
                'connection_status' => 'Connected',
                'tables' => \DB::select('SHOW TABLES'),
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connection_status' => 'Failed',
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself'
            ], 500);
        }
    })->name('debug.database');

    // Configuration Information
    Route::get('/debug/config', function () {
        return response()->json([
            'app_config' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'database_config' => [
                'default' => config('database.default'),
                'connections' => array_keys(config('database.connections')),
            ],
            'cache_config' => [
                'default' => config('cache.default'),
                'stores' => array_keys(config('cache.stores')),
            ],
            'session_config' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
                'encrypt' => config('session.encrypt'),
            ],
            'mail_config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'from' => config('mail.from'),
            ],
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself'
        ]);
    })->name('debug.config');

    // Cache Information
    Route::get('/debug/cache', function () {
        try {
            $cacheKey = 'debug_test_' . time();
            $testValue = 'Debug test value';

            \Cache::put($cacheKey, $testValue, 60);
            $retrievedValue = \Cache::get($cacheKey);
            \Cache::forget($cacheKey);

            return response()->json([
                'cache_driver' => config('cache.default'),
                'cache_test' => [
                    'stored_value' => $testValue,
                    'retrieved_value' => $retrievedValue,
                    'test_passed' => $testValue === $retrievedValue,
                ],
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'cache_driver' => config('cache.default'),
                'error' => $e->getMessage(),
                'timestamp' => '2025-07-31 15:23:41',
                'user' => 'redeemself',
                'current_login' => 'redeemself'
            ], 500);
        }
    })->name('debug.cache');

    // Environment Variables
    Route::get('/debug/env', function () {
        $envVars = [
            'APP_NAME' => env('APP_NAME'),
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'APP_URL' => env('APP_URL'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'CACHE_DRIVER' => env('CACHE_DRIVER'),
            'SESSION_DRIVER' => env('SESSION_DRIVER'),
            'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
            'MAIL_MAILER' => env('MAIL_MAILER'),
        ];

        return response()->json([
            'environment_variables' => $envVars,
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself'
        ]);
    })->name('debug.env');
}

// -----------------------------
// CUSTOM ERROR ROUTES
// -----------------------------
foreach ([401, 403, 404, 419, 422, 429, 500, 503] as $code) {
    Route::get("/error/$code", function () use ($code) {
        return response()->view("errors.$code", [
            'timestamp' => '2025-07-31 15:23:41',
            'user' => 'redeemself',
            'current_login' => 'redeemself'
        ], $code);
    })->name("error.$code");
}

// -----------------------------
// FALLBACK ROUTE (redirect to home)
// -----------------------------
Route::fallback(function () {
    \Log::info('Fallback route accessed', [
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'timestamp' => '2025-07-31 15:23:41',
        'user' => 'redeemself',
        'current_login' => 'redeemself'
    ]);

    return redirect()->route('home')->with('error', 'Halaman yang Anda cari tidak ditemukan.');
});
