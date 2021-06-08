<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiTagsController extends Controller
{
    /**
     * Tags json response for select2 component
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(
            Tag::select(['id', 'name'])
                ->whereRaw('lower(name)  like (?)', ["%{$request['query']}%"])
                ->get(),
        );
    }
}
