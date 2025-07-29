<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserAddress
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && method_exists($user, 'addresses') && $user->addresses()->count() === 0) {
            return redirect()->route('user.profile.index')
                ->with('error', 'Anda harus mengisi alamat rumah sebelum dapat membeli produk!');
        }
        return $next($request);
    }
}
