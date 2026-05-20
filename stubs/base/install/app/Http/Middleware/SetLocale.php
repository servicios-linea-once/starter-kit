<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): mixed  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->session()->get('locale', config('app.locale', 'es'));

        if (in_array($locale, ['es', 'en'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
