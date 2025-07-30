<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddJsContentTypeHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the requested path is a JavaScript file in the vendor directory
        $path = $request->path();
        if (
            strpos($path, 'js/vendor/') !== false &&
            (str_ends_with($path, '.js') || str_ends_with($path, '.mjs'))
        ) {
            // Set the correct MIME type for JavaScript modules
            $response->header('Content-Type', 'application/javascript');
        }

        return $response;
    }
}
