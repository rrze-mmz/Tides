<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! session()->has('locale')) {
            session()->put('locale', config('app.locale'));
        }

        App::setLocale(session('locale'));

//        dd(app()->getLocale());
        return $next($request);
    }
}
