<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PolicyAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Contoh: cek kolom 'accepted_policy_at' di users table
        // Pastikan di User model ada attribute accepted_policy_at atau method hasAcceptedPolicy()
        $user = $request->user();
        if (! $user || ! $user->accepted_policy_at) {
            return redirect()->route('policy.accept.form');
        }

        return $next($request);
    }
}
