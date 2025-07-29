<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePolicyAccepted
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->accepted_policy_at) {
            return redirect()->route('policy.accept.form');
        }
        return $next($request);
    }
}
