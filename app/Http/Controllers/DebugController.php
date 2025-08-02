<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

/**
 * DebugController - System Diagnostics
 * 
 * Updated: 2025-08-02 02:48:14 UTC by gerrymulyadi709
 * 
 * FEATURES:
 * ✅ Fixed return type declarations
 * ✅ Complete controller diagnostics
 * ✅ Route diagnostics and validation
 * ✅ Cache management utilities
 * ✅ Error handling and logging
 */
class DebugController extends Controller
{
    /**
     * Check if ProductController exists and diagnose loading issues
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkController(): JsonResponse
    {
        $timestamp = '2025-08-02 02:48:14';
        $username = 'gerrymulyadi709';

        try {
            // Check main ProductController
            $mainControllerPath = app_path('Http/Controllers/ProductController.php');
            $mainControllerExists = File::exists($mainControllerPath);

            // Check User\ProductController
            $userControllerPath = app_path('Http/Controllers/User/ProductController.php');
            $userControllerExists = File::exists($userControllerPath);

            // Check User\CartController
            $cartControllerPath = app_path('Http/Controllers/User/CartController.php');
            $cartControllerExists = File::exists($cartControllerPath);

            // Check PublicController
            $publicControllerPath = app_path('Http/Controllers/PublicController.php');
            $publicControllerExists = File::exists($publicControllerPath);

            $controllerDiagnostics = [];

            // Check if controllers can be instantiated
            $controllersToCheck = [
                'User\ProductController' => 'App\Http\Controllers\User\ProductController',
                'User\CartController' => 'App\Http\Controllers\User\CartController',
                'PublicController' => 'App\Http\Controllers\PublicController'
            ];

            foreach ($controllersToCheck as $name => $className) {
                $diagnostic = [
                    'name' => $name,
                    'class' => $className,
                    'exists' => class_exists($className),
                    'instantiable' => false,
                    'methods' => [],
                    'error' => null
                ];

                try {
                    if (class_exists($className)) {
                        $reflection = new ReflectionClass($className);
                        $diagnostic['instantiable'] = $reflection->isInstantiable();

                        // Get public methods
                        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                        $diagnostic['methods'] = array_map(function ($method) {
                            return $method->getName();
                        }, $methods);
                    }
                } catch (\Exception $e) {
                    $diagnostic['error'] = $e->getMessage();
                }

                $controllerDiagnostics[] = $diagnostic;
            }

            // Check Model classes
            $modelsToCheck = [
                'Product' => 'App\Models\Product',
                'Cart' => 'App\Models\Cart',
                'Category' => 'App\Models\Category',
                'User' => 'App\Models\User'
            ];

            $modelDiagnostics = [];
            foreach ($modelsToCheck as $name => $className) {
                $modelDiagnostics[] = [
                    'name' => $name,
                    'class' => $className,
                    'exists' => class_exists($className),
                    'file_exists' => File::exists(app_path("Models/{$name}.php"))
                ];
            }

            // Check autoload
            $autoloadPath = base_path('vendor/autoload.php');
            $autoloadExists = File::exists($autoloadPath);

            // Check composer dump-autoload status
            $composerLockPath = base_path('composer.lock');
            $composerLockExists = File::exists($composerLockPath);

            // Log diagnostic run
            Log::info("Controller diagnostic run", [
                'main_controller_exists' => $mainControllerExists,
                'user_controller_exists' => $userControllerExists,
                'cart_controller_exists' => $cartControllerExists,
                'autoload_exists' => $autoloadExists,
                'timestamp' => $timestamp,
                'username' => $username
            ]);

            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'success',
                'controllers' => [
                    'main_product_controller' => [
                        'path' => $mainControllerPath,
                        'exists' => $mainControllerExists
                    ],
                    'user_product_controller' => [
                        'path' => $userControllerPath,
                        'exists' => $userControllerExists
                    ],
                    'cart_controller' => [
                        'path' => $cartControllerPath,
                        'exists' => $cartControllerExists
                    ],
                    'public_controller' => [
                        'path' => $publicControllerPath,
                        'exists' => $publicControllerExists
                    ]
                ],
                'controller_diagnostics' => $controllerDiagnostics,
                'model_diagnostics' => $modelDiagnostics,
                'system' => [
                    'autoload_exists' => $autoloadExists,
                    'composer_lock_exists' => $composerLockExists,
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version()
                ],
                'suggestions' => [
                    'if_missing_controllers' => 'Run: php artisan make:controller User/ProductController',
                    'if_autoload_issues' => 'Run: composer dump-autoload',
                    'if_cache_issues' => 'Run: php artisan optimize:clear',
                    'if_class_not_found' => 'Check namespace declarations and file locations'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Controller diagnostic failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => $timestamp,
                'username' => $username
            ]);

            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'error',
                'message' => 'Controller diagnostic failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'suggestion' => 'Check the Laravel error logs for more details'
            ], 500);
        }
    }

    /**
     * Check routes and diagnose route loading issues
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRoutes(): JsonResponse
    {
        $timestamp = '2025-08-02 02:48:14';
        $username = 'gerrymulyadi709';

        try {
            // Get all routes in the application
            $routes = collect(Route::getRoutes())->map(function ($route) {
                return [
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'methods' => $route->methods(),
                    'action' => $route->getActionName(),
                    'middleware' => $route->middleware(),
                ];
            });

            // Check specific routes
            $routesToCheck = [
                'home',
                'products.index',
                'products.show',
                'cart.index',
                'cart.add',
                'login',
                'register'
            ];

            $routeStatus = [];
            foreach ($routesToCheck as $routeName) {
                $route = $routes->firstWhere('name', $routeName);
                $routeStatus[$routeName] = [
                    'exists' => !is_null($route),
                    'details' => $route,
                    'url' => null,
                    'url_error' => null
                ];

                // Try to generate URL
                try {
                    if (!is_null($route)) {
                        $routeStatus[$routeName]['url'] = route($routeName);
                    }
                } catch (\Exception $e) {
                    $routeStatus[$routeName]['url_error'] = $e->getMessage();
                }
            }

            // Check if route cache exists
            $routeCacheExists = File::exists(base_path('bootstrap/cache/routes-v7.php'));

            // Try to clear route cache
            $clearCacheOutput = null;
            try {
                Artisan::call('route:clear');
                $clearCacheOutput = Artisan::output();
            } catch (\Exception $e) {
                $clearCacheOutput = "Error: " . $e->getMessage();
            }

            // Check routes file
            $routesFilePath = base_path('routes/web.php');
            $routesFileExists = File::exists($routesFilePath);
            $routesFileContent = $routesFileExists ? File::get($routesFilePath) : 'File not found';
            $containsHomeRoute = str_contains($routesFileContent, "->name('home')");
            $containsProductRoutes = str_contains($routesFileContent, "products.index");
            $containsCartRoutes = str_contains($routesFileContent, "cart.add");

            // Log this diagnostic run
            Log::info("Route diagnostic run", [
                'total_routes' => $routes->count(),
                'route_cache_exists' => $routeCacheExists,
                'routes_file_exists' => $routesFileExists,
                'contains_home_route' => $containsHomeRoute,
                'timestamp' => $timestamp,
                'username' => $username
            ]);

            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'success',
                'route_status' => $routeStatus,
                'routes_file' => [
                    'path' => $routesFilePath,
                    'exists' => $routesFileExists,
                    'contains_home_route' => $containsHomeRoute,
                    'contains_product_routes' => $containsProductRoutes,
                    'contains_cart_routes' => $containsCartRoutes,
                    'file_size' => $routesFileExists ? File::size($routesFilePath) : 0,
                ],
                'route_cache' => [
                    'exists' => $routeCacheExists,
                    'clear_output' => $clearCacheOutput,
                ],
                'statistics' => [
                    'total_routes' => $routes->count(),
                    'named_routes' => $routes->filter(function ($route) {
                        return !is_null($route['name']);
                    })->count(),
                    'get_routes' => $routes->filter(function ($route) {
                        return in_array('GET', $route['methods']);
                    })->count(),
                    'post_routes' => $routes->filter(function ($route) {
                        return in_array('POST', $route['methods']);
                    })->count(),
                ],
                'fix_commands' => [
                    'clear_route_cache' => 'php artisan route:clear',
                    'clear_all_cache' => 'php artisan optimize:clear',
                    'clear_config' => 'php artisan config:clear',
                    'clear_view_cache' => 'php artisan view:clear',
                    'route_list' => 'php artisan route:list'
                ],
                'suggestions' => $this->generateRouteSuggestions($routeStatus, $containsHomeRoute)
            ]);
        } catch (\Exception $e) {
            Log::error('Route diagnostic failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => $timestamp,
                'username' => $username
            ]);

            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'error',
                'message' => 'Route diagnostic failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'suggestion' => 'Check the Laravel error logs and ensure routes/web.php exists'
            ], 500);
        }
    }

    /**
     * Clear all application caches
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCaches(): JsonResponse
    {
        $timestamp = '2025-08-02 02:48:14';
        $username = 'gerrymulyadi709';

        try {
            $results = [];

            $commands = [
                'config:clear' => 'Clear configuration cache',
                'cache:clear' => 'Clear application cache',
                'route:clear' => 'Clear route cache',
                'view:clear' => 'Clear view cache',
                'event:clear' => 'Clear event cache'
            ];

            foreach ($commands as $command => $description) {
                try {
                    Artisan::call($command);
                    $results[$command] = [
                        'success' => true,
                        'description' => $description,
                        'output' => Artisan::output()
                    ];
                } catch (\Exception $e) {
                    $results[$command] = [
                        'success' => false,
                        'description' => $description,
                        'error' => $e->getMessage()
                    ];
                }
            }

            Log::info('Cache clearing completed', [
                'results' => $results,
                'timestamp' => $timestamp,
                'username' => $username
            ]);

            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'success',
                'message' => 'Cache clearing completed',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'timestamp' => $timestamp,
                'username' => $username,
                'status' => 'error',
                'message' => 'Cache clearing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate route suggestions based on diagnostic results
     *
     * @param array $routeStatus
     * @param bool $containsHomeRoute
     * @return array
     */
    private function generateRouteSuggestions(array $routeStatus, bool $containsHomeRoute): array
    {
        $suggestions = [];

        if (!$routeStatus['home']['exists']) {
            if (!$containsHomeRoute) {
                $suggestions[] = 'Add home route to routes/web.php: Route::get("/", [PublicController::class, "home"])->name("home");';
            } else {
                $suggestions[] = 'Home route exists in file but not loaded. Try clearing route cache.';
            }
        }

        if (!$routeStatus['products.index']['exists']) {
            $suggestions[] = 'Add product routes to routes/web.php or check ProductController exists.';
        }

        if (!$routeStatus['cart.add']['exists']) {
            $suggestions[] = 'Add cart routes to routes/web.php or check CartController exists.';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'All critical routes appear to be configured correctly.';
        }

        return $suggestions;
    }
}
