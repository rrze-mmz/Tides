<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RememberPreviousUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->is('login', 'logout', 'register', 'password/*', 'verify-email/*', 'verified/*')) {
            session(['url.intended' => url()->current()]);
        }

        return $next($request);
    }
}
