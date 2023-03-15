<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesMembershipRequest;
use App\Models\Series;
use App\Models\User;
use App\Notifications\SeriesOwnershipAddUser;
use App\Notifications\SeriesOwnershipRemoveUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use function to_route;

class SeriesOwnership extends Controller
{
    /**
     * Handle the incoming request.
     *
     *
     * @throws AuthorizationException
     */
    public function __invoke(Series $series, SeriesMembershipRequest $request): RedirectResponse
    {
        $this->authorize('change-series-owner');

        $validated = $request->validated();

        $user = User::findOrFail($validated['userID']);

        if (! is_null($series->owner)) {
            $series->owner->notify(new SeriesOwnershipRemoveUser($series));
        }

        $series->owner_id = $user->id;
        $series->save();
        $user->notify(new SeriesOwnershipAddUser($series));

        return to_route('series.edit', $series);
    }
}
