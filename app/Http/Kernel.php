<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;

// Middleware bawaan dan custom
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DevMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsurePolicyAccepted;

// Additional Laravel middleware imports
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Routing\Middleware\ThrottleRequests;

/**
 * HTTP Kernel Configuration
 * Azka Garden E-Commerce Application
 *
 * @updated 2025-08-02 09:52:17 by gerrymulyadi709
 * - FIXED all PHP6601 warnings by simplifying class names
 * - FIXED middleware pipeline issues for AJAX handling
 * - Enhanced CSRF token validation for cart operations
 * - Improved middleware stack configuration
 * - Added proper exception handling
 * - Fixed cart AJAX request handling
 * - Enhanced error handling and logging
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Core Laravel middleware - FIXED ORDER
        \Illuminate\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     * @updated 2025-08-02 09:52:17 by gerrymulyadi709
     * FIXED: Enhanced web middleware group for proper AJAX and CSRF handling
     */
    protected $middlewareGroups = [
        'web' => [
            // FIXED: Proper order for AJAX and cart functionality
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,

            // FIXED: Ensure CSRF token validation is active
            VerifyCsrfToken::class, // Pastikan ini ada untuk cart AJAX requests

            SubstituteBindings::class,

            // ENHANCED: Additional middleware for better user experience
            HandleCors::class, // FIXED: Simplified from \Illuminate\Http\Middleware\HandleCors
        ],

        'api' => [
            // Laravel Sanctum for API authentication
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class . ':api',
            SubstituteBindings::class,
        ],

        // ENHANCED: Admin middleware group
        'admin' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            'auth:admin',
        ],

        // ENHANCED: User middleware group for enhanced security
        'user' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            'auth',
        ],
    ];

    /**
     * The application's named route middleware.
     *
     * @var array<string, class-string|string>
     * @updated 2025-08-02 09:52:17 by gerrymulyadi709
     * ENHANCED: Added middleware for better cart and AJAX handling
     */
    protected $middlewareAliases = [
        // Authentication middleware
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => SetCacheHeaders::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'password.confirm' => RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
        'verified' => EnsureEmailIsVerified::class,

        // Role-based middleware
        'admin' => AdminMiddleware::class,
        'developer' => DevMiddleware::class,
        'user' => UserMiddleware::class,

        // Policy acceptance middleware
        'policy.accepted' => EnsurePolicyAccepted::class,

        // ENHANCED: Additional middleware for cart operations
        'csrf' => VerifyCsrfToken::class,
        'cors' => HandleCors::class,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @updated 2025-08-02 09:52:17 by gerrymulyadi709
     * ENHANCED: Better request handling for AJAX and cart operations
     */
    public function handle($request)
    {
        try {
            // ENHANCED: More detailed logging for AJAX requests
            $isAjax = $request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

            \Log::info('Request processing started', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_ajax' => $isAjax,
                'content_type' => $request->header('Content-Type'),
                'accept' => $request->header('Accept'),
                'x_requested_with' => $request->header('X-Requested-With'),
                'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing',
                'user_id' => auth()->id(),
                'timestamp' => '2025-08-02 09:52:17',
                'user' => 'gerrymulyadi709'
            ]);

            // ENHANCED: Pre-process AJAX requests for better compatibility
            if ($isAjax) {
                // Ensure proper headers for AJAX requests
                if (!$request->header('Accept')) {
                    $request->headers->set('Accept', 'application/json');
                }

                // Log AJAX-specific details
                \Log::info('AJAX request detected', [
                    'route' => $request->route() ? $request->route()->getName() : 'unknown',
                    'parameters' => $request->route() ? $request->route()->parameters() : [],
                    'request_data' => $request->all(),
                    'timestamp' => '2025-08-02 09:52:17',
                    'user' => 'gerrymulyadi709'
                ]);
            }

            $response = parent::handle($request);

            // ENHANCED: Log response details for debugging
            \Log::info('Request processed successfully', [
                'status_code' => $response->getStatusCode(),
                'content_type' => $response->headers->get('Content-Type'),
                'is_ajax' => $isAjax,
                'response_size' => strlen($response->getContent()),
                'timestamp' => '2025-08-02 09:52:17',
                'user' => 'gerrymulyadi709'
            ]);

            // ENHANCED: Add CORS headers for AJAX requests
            if ($isAjax) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            }

            return $response;
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // ENHANCED: Handle CSRF token mismatch for AJAX requests
            \Log::warning('CSRF token mismatch', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'is_ajax' => $request->ajax(),
                'user_id' => auth()->id(),
                'timestamp' => '2025-08-02 09:52:17',
                'user' => 'gerrymulyadi709'
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'CSRF token mismatch. Please refresh the page and try again.',
                    'error_code' => 'CSRF_MISMATCH'
                ], 419);
            }

            // Re-throw for regular requests
            throw $e;
        } catch (\Throwable $e) {
            // ENHANCED: Better error handling for different request types
            \Log::error('Request processing failed', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'is_ajax' => $request->ajax() || $request->wantsJson(),
                'user_id' => auth()->id(),
                'request_data' => $this->sanitizeRequestData($request->all()),
                'stack_trace' => $e->getTraceAsString(),
                'timestamp' => '2025-08-02 09:52:17',
                'user' => 'gerrymulyadi709'
            ]);

            // ENHANCED: Return JSON error for AJAX requests
            if (($request->ajax() || $request->wantsJson()) && !config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your request.',
                    'error_code' => 'INTERNAL_ERROR'
                ], 500);
            }

            // Re-throw the exception to let Laravel handle it properly
            throw $e;
        }
    }

    /**
     * Sanitize request data for logging
     *
     * @param array $data
     * @return array
     */
    private function sanitizeRequestData(array $data): array
    {
        // Remove sensitive data from logs
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    /**
     * Get the priority-sorted list of middleware.
     *
     * Forces the listed middleware to always be in the given order.
     *
     * @var string[]
     * @updated 2025-08-02 09:52:17 by gerrymulyadi709
     * FIXED: Simplified class names to resolve PHP6601 warnings
     */
    protected $middlewarePriority = [
        \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        EncryptCookies::class, // FIXED: Simplified from \Illuminate\Cookie\Middleware\EncryptCookies
        StartSession::class, // FIXED: Simplified from \Illuminate\Session\Middleware\StartSession
        ShareErrorsFromSession::class, // FIXED: Simplified from \Illuminate\View\Middleware\ShareErrorsFromSession
        \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
        ThrottleRequests::class, // FIXED: Simplified from \Illuminate\Routing\Middleware\ThrottleRequests
        \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
        \Illuminate\Contracts\Session\Middleware\AuthenticatesSessions::class,
        SubstituteBindings::class, // FIXED: Simplified from \Illuminate\Routing\Middleware\SubstituteBindings
        Authorize::class, // FIXED: Simplified from \Illuminate\Auth\Middleware\Authorize
    ];
}
