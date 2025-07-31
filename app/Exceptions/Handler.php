<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;

/**
 * Application Exception Handler
 * Updated: 2025-07-31 14:46:39 by redeemself
 * Fixed all property visibility and simplification issues
 * Enhanced error handling and logging for Azka Garden E-Commerce
 */
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        '_token',
        'csrf_token',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Enhanced logging with more context
            \Log::error('Application exception occurred', [
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself',
                'application' => 'Azka Garden E-Commerce'
            ]);
        });

        // Handle specific cart-related exceptions
        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error' => 'Data yang diminta tidak ditemukan',
                    'timestamp' => '2025-07-31 14:46:39',
                    'user' => 'redeemself'
                ], 404);
            }

            return redirect()->route('home')
                ->with('error', 'Data yang Anda cari tidak ditemukan.');
        });

        // Handle validation exceptions for cart operations
        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error' => 'Data yang dimasukkan tidak valid',
                    'errors' => $e->errors(),
                    'timestamp' => '2025-07-31 14:46:39',
                    'user' => 'redeemself'
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang dimasukkan.');
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        // Log the exception for debugging
        \Log::debug('Exception being rendered', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        // Handle specific exceptions
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return $this->handleCsrfException($request, $e);
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return $this->handleNotFound($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return $this->handleUnauthenticated($request, $e);
        }

        if ($e instanceof QueryException) {
            return $this->handleDatabaseException($request, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->handleMethodNotAllowed($request, $e);
        }

        // Handle cart-specific exceptions
        if (str_contains($request->path(), 'cart') || str_contains($request->path(), 'checkout')) {
            return $this->handleCartException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle CSRF token mismatch exceptions.
     */
    protected function handleCsrfException(Request $request, Throwable $e): Response
    {
        \Log::warning('CSRF token mismatch detected', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'CSRF token mismatch.',
                'error' => 'Sesi telah berakhir, silakan refresh halaman',
                'redirect' => $request->url(),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 419);
        }

        return redirect()->back()
            ->withInput($request->except('_token'))
            ->with('error', 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.')
            ->with('csrf_error', true);
    }

    /**
     * Handle not found exceptions.
     */
    protected function handleNotFound(Request $request, Throwable $e): Response
    {
        \Log::info('404 Not Found', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'error' => 'Halaman yang diminta tidak ditemukan',
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 404);
        }

        // Check if custom 404 view exists
        if (view()->exists('errors.404')) {
            return response()->view('errors.404', [
                'message' => 'Halaman yang Anda cari tidak ditemukan.',
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 404);
        }

        return redirect()->route('home')
            ->with('error', 'Halaman yang Anda cari tidak ditemukan.');
    }

    /**
     * Handle unauthenticated exceptions.
     */
    protected function handleUnauthenticated(Request $request, AuthenticationException $e): Response
    {
        \Log::info('Unauthenticated access attempt', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'Silakan login terlebih dahulu',
                'redirect' => route('login'),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 401);
        }

        return redirect()->guest(route('login'))
            ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
    }

    /**
     * Handle database exceptions.
     */
    protected function handleDatabaseException(Request $request, QueryException $e): Response
    {
        \Log::error('Database exception occurred', [
            'message' => $e->getMessage(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'sql' => method_exists($e, 'getSql') ? $e->getSql() : 'N/A',
            'bindings' => method_exists($e, 'getBindings') ? $e->getBindings() : [],
            'error_code' => $e->getCode(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'error' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada database',
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan pada sistem. Silakan coba lagi.')
            ->withInput();
    }

    /**
     * Handle method not allowed exceptions.
     */
    protected function handleMethodNotAllowed(Request $request, MethodNotAllowedHttpException $e): Response
    {
        // Get allowed methods from the exception
        $allowedMethods = 'N/A';
        if (method_exists($e, 'getAllowedMethods')) {
            $allowedMethods = implode(', ', $e->getAllowedMethods());
        }

        \Log::warning('Method not allowed', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'allowed_methods' => $allowedMethods,
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed.',
                'error' => 'Metode HTTP tidak diizinkan untuk endpoint ini',
                'allowed_methods' => $allowedMethods,
                'current_method' => $request->method(),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 405);
        }

        return redirect()->route('home')
            ->with('error', 'Metode akses tidak diizinkan.');
    }

    /**
     * Handle cart-specific exceptions.
     */
    protected function handleCartException(Request $request, Throwable $e): Response
    {
        \Log::error('Cart operation exception', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'cart_data' => $request->all(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart operation failed.',
                'error' => 'Terjadi kesalahan pada operasi keranjang',
                'redirect' => route('cart.index'),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 500);
        }

        return redirect()->route('cart.index')
            ->with('error', 'Terjadi kesalahan pada keranjang belanja. Silakan coba lagi.')
            ->withInput();
    }

    /**
     * Convert an authentication exception into a response.
     * FIXED: Removed problematic property access
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        \Log::info('Authentication exception handled', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'guards' => $exception->guards(),
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error' => 'Silakan login terlebih dahulu',
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ], 401);
        }

        // FIXED: Simple redirect to login without accessing protected properties
        $redirectTo = route('login');

        return redirect()->guest($redirectTo)
            ->with('info', 'Silakan login untuk melanjutkan.');
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    protected function context(): array
    {
        try {
            return array_filter([
                'userId' => auth()->id(),
                'sessionId' => session()->getId(),
                'ip' => request()->ip(),
                'userAgent' => request()->userAgent(),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself',
                'application' => 'Azka Garden E-Commerce'
            ]);
        } catch (Throwable $e) {
            return [
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself',
                'application' => 'Azka Garden E-Commerce',
                'context_error' => 'Failed to retrieve context'
            ];
        }
    }

    /**
     * Get the detailed exception information for logging.
     *
     * @param  Throwable  $e
     * @return array
     */
    protected function getExceptionDetails(Throwable $e): array
    {
        return [
            'exception_class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'previous' => $e->getPrevious() ? get_class($e->getPrevious()) : null,
            'timestamp' => '2025-07-31 14:46:39',
            'user' => 'redeemself'
        ];
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param  Throwable  $e
     * @return bool
     */
    public function shouldReport(Throwable $e)
    {
        // Don't report certain exceptions in development
        if (config('app.debug')) {
            $ignoreInDev = [
                \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
                \Illuminate\Session\TokenMismatchException::class,
            ];

            if (in_array(get_class($e), $ignoreInDev)) {
                return false;
            }
        }

        return parent::shouldReport($e);
    }

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $e
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $e)
    {
        // Add custom reporting logic for specific exceptions
        if ($e instanceof QueryException) {
            \Log::channel('database')->error('Database query failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'sql' => method_exists($e, 'getSql') ? $e->getSql() : 'N/A',
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ]);
        }

        if (str_contains(request()->path(), 'cart') || str_contains(request()->path(), 'checkout')) {
            \Log::channel('cart')->error('Cart operation failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'cart_data' => session()->get('cart_items', []),
                'timestamp' => '2025-07-31 14:46:39',
                'user' => 'redeemself'
            ]);
        }

        parent::report($e);
    }
}
