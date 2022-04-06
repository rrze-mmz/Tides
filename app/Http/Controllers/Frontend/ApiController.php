<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Models\Clip;
use App\Models\Organization;
use App\Models\Presenter;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    public function clips(ApiRequest $request): JsonResponse
    {
        $clips = Clip::search($request->validated(['query']))->get();

        return response()->json($clips->map(function ($clip) {
            return [
                'id'   => $clip->id,
                'name' => $clip->title,
            ];
        }));
    }

    /**
     * Tags json response for select2 component
     *
     * @param ApiRequest $request
     * @return JsonResponse
     */
    public function tags(ApiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return response()->json(
            Tag::select(['id', 'name'])
                ->search($validated['query'])
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

        return response()->json(
            Organization::select(['org_id as id', 'name'])
                ->search($validated['query'])
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

        $presenters = Presenter::search($validated['query'])->get();

        $names = $presenters->map(function ($presenter) {
            return [
                'id'   => $presenter->id,
                'name' => $presenter->getFullNameAttribute()
            ];
        });
        return response()->json($names);
    }

    /**
     * Presenters json response for select2 component
     *
     * @param ApiRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function users(ApiRequest $request): JsonResponse
    {
        $this->authorize('dashboard', auth()->user());

        $validated = $request->validated();

        $users = User::search($validated['query'])->moderators()->get();

        $names = $users->map(function ($user) {
            return [
                'id'   => $user->id,
                'name' => $user->getFullNameAttribute() . '/' . str()->mask($user->username, '*', 3),
            ];
        });
        return response()->json($names);
    }
}
