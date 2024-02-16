<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::info('Current URL in Middleware is:'.url()->current());
            session(['url.intended' => url()->current()]);
        }

        return $next($request);
    }
}
