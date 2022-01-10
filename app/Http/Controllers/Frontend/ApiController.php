<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Models\Organization;
use App\Models\Presenter;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Tags json response for select2 component
     *
     * @param ApiRequest $request
     * @return JsonResponse
     */
    public function tags(ApiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $searchTerm = strtolower($validated['query']);

        return response()->json(
            Tag::select(['id', 'name'])
                ->whereRaw('lower(name)  like (?)', ["%{$searchTerm}%"])
                ->get(),
        );
    }

    /**
     * Organizations json response for select2 component
     *
     * @param ApiRequest $request
     * @return JsonResponse
     */
    public function organizations(ApiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $searchTerm = strtolower($validated['query']);

        return response()->json(
            Organization::select(['org_id as id', 'name'])
                ->whereRaw('lower(name)  like (?)', ["%{$searchTerm}%"])
                ->get(),
        );
    }

    /**
     * Presenters json response for select2 component
     *
     * @param ApiRequest $request
     * @return JsonResponse
     */
    public function presenters(ApiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $searchTerm = strtolower($validated['query']);

        $presenters = Presenter::whereRaw('lower(first_name)  like (?)', ["%{$searchTerm}%"])
            ->orWhereRaw('lower(last_name)  like (?)', ["%{$searchTerm}%"])->get();

        $names = $presenters->map(function ($presenter) {
            return [
                'id'   => $presenter->id,
                'name' => $presenter->getFullNameAttribute()
            ];
        });
        return response()->json($names);
    }
}
