<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    public function handle($request, Closure $next, $guard = null)
    {
        $locale = null;

        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }else{
            $locale = 'pt-BR';
        }

        App::setLocale($locale);

        return $next($request);

    }

}