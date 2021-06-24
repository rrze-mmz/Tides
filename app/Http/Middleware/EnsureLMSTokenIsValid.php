<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureLMSTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lmsRequest = collect([
            'type'     => ((string)$request->segment('3')==='course')?'series':(string)$request->segment('3'),
            'id'       => (int) $request->segment('4'),
            'token'    => $request->segment('5'),
            'duration' => $request->segment('6'),
            'source'   => $request->segment('7')
        ]);

        $model = "App\Models\\".ucfirst($lmsRequest['type']);

        $obj = $model::find($lmsRequest['id']);

        return $next($request);
    }
}
