<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->query('lang')
            ?? Session::get('locale')
            ?? Cookie::get('locale')
            ?? 'es';

        $locale = in_array($locale, ['es', 'en']) ? $locale : 'es';

        app()->setLocale($locale);

        if ($request->query('lang')) {
            Session::put('locale', $locale);
            Cookie::queue('locale', $locale, 60 * 24 * 365);
        }

        return $next($request);
    }
}
