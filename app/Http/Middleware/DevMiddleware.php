<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Developer;

class DevMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Developer|null $developer */
        $developer = Auth::guard('developer')->user();

        if (! $developer || ! $developer->isDeveloper()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
