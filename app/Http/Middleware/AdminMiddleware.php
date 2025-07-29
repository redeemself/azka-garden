<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();

        // Sekarang Intelephense tahu $admin adalah Admin, jadi method isAdmin() dikenali
        if (! $admin || ! $admin->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
