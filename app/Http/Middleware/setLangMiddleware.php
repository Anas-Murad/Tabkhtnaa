<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class setLangMiddleware
{
    public function handle(Request $request, Closure $next)
    {



        $lang = $request->input('lang') ?? $request->header('lang');
        if ($lang && in_array($lang, ['ar', 'en'], true)) {
            app()->setLocale($lang);
        } else {
            app()->setLocale('ar');
        }
        return $next($request);
    }
}
