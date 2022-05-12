<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureLMSTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        //this should either be a series (course term is for backward compability with old LMS links) or a clip
        $objType = ((string)$request->segment('3') === 'course') ? 'series' : (string)$request->segment('3');
        $objID = (int)$request->segment('4');
        $objToken = $request->segment('5');
        $objTokenTime = $request->segment('6');

        $model = "App\Models\\" . ucfirst($objType);

        $obj = $model::find($objID);

        if (!$obj->acls()->pluck('id')->contains('4')) {
            return $next($request);
        }

        $token = generateLMSToken($obj, $objTokenTime);

        if ($objToken !== $token) {
            abort(403);
        }

        return $next($request);
    }
}
