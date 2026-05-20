<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorChallengeIsComplete
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): mixed  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->session()->has('login.id') && ! $request->routeIs('two-factor.*')) {
            return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}
