<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, string $guard = null)
    {
        if ($guard === 'admin' && !Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        if ($guard === 'web' && !Auth::check()) {
            return redirect()->route('users.login');
        }

        return $next($request);
    }
}
