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
 * @updated 2025-07-31 14:36:47 by DenuJanuari
 * - Fixed middleware pipeline issues
 * - Enhanced error handling and logging
 * - Improved middleware stack configuration
 * - Added proper exception handling
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
     * @updated 2025-07-31 14:36:47 by DenuJanuari
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            // Laravel Sanctum
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ThrottleRequests::class . ':api',
            SubstituteBindings::class,
        ],
    ];

    /**
     * The application's named route middleware.
     *
     * @var array<string, class-string|string>
     * @updated 2025-07-31 14:36:47 by DenuJanuari
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
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function handle($request)
    {
        try {
            \Log::info('Request processing started', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => '2025-07-31 14:36:47',
                'user' => 'DenuJanuari'
            ]);

            $response = parent::handle($request);

            \Log::info('Request processed successfully', [
                'status_code' => $response->getStatusCode(),
                'timestamp' => '2025-07-31 14:36:47',
                'user' => 'DenuJanuari'
            ]);

            return $response;
        } catch (\Throwable $e) {
            \Log::error('Request processing failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'timestamp' => '2025-07-31 14:36:47',
                'user' => 'DenuJanuari'
            ]);

            // Re-throw the exception to let Laravel handle it properly
            throw $e;
        }
    }
}
