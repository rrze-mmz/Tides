<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Tags json response for select2 component
     * @param Request $request
     * @return JsonResponse
     */
    public function tags(Request $request): JsonResponse
    {
        return response()->json(
            Tag::select(['id', 'name'])
                ->whereRaw('lower(name)  like (?)', ["%{$request['query']}%"])
                ->get(),
        );
    }
    public function organizations(Request $request)
    {
        return response()->json(
            Organization::select(['org_id as id', 'name'])
                ->whereRaw('lower(name)  like (?)', ["%{$request['query']}%"])
                ->get(),
        );
    }
}
