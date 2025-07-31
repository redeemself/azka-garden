<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * CSRF Token Verification Middleware
 * Updated: 2025-07-31 14:36:47 by DenuJanuari
 * Enhanced with better error handling
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'webhook/*',
        'dev/login',
        'dev/register',
        'admin/login',
        'admin/register',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            \Log::warning('CSRF token mismatch', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => '2025-07-31 14:36:47',
                'user' => 'DenuJanuari'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CSRF token mismatch.',
                    'error' => 'Token tidak valid',
                    'timestamp' => '2025-07-31 14:36:47'
                ], 419);
            }

            return redirect()->back()
                ->withInput($request->except('_token'))
                ->withErrors(['csrf' => 'Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.']);
        }
    }
}
