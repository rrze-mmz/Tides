<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AcceptUseTerms
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (auth()->user()->settings()->data['accept_use_terms']) {
            return $next($request);
        } else {
            return \response()->view('frontend.myPortal.useTerms');
        }
    }
}
