<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * Trust Proxies Middleware
 * Updated: 2025-07-31 14:36:47 by DenuJanuari
 * Fixed proxy configuration for local development
 */
class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = [
        '127.0.0.1',
        '::1',
        'localhost',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Throwable $e) {
            \Log::error('TrustProxies middleware error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_url' => $request->fullUrl(),
                'timestamp' => '2025-07-31 14:36:47',
                'user' => 'DenuJanuari'
            ]);

            // Continue without proxy trust if there's an error
            return $next($request);
        }
    }
}
