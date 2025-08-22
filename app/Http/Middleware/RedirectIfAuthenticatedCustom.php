<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle($request, Closure $next, string $guard = null)
    {
        if ($guard === 'admin' && Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if ($guard === 'web' && Auth::check()) {
            return redirect()->route('users.dashboard');
        }

        return $next($request);
    }
}
