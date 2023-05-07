<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class setLangMiddleware
{
    public function handle(Request $request, Closure $next)
    {



        //  todo 45
        if ($request->filled('lang')   &&  in_array($request->filled('lang') , ['ar' , 'en']) ){
            app()->setLocale($request->lang);
        }else{
            app()->setLocale('ae');
        }
        return $next($request);
    }
}
