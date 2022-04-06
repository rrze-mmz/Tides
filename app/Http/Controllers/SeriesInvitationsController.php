<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesInvitationRequest;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class SeriesInvitationsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Series $series
     * @param SeriesInvitationRequest $request
     * @return RedirectResponse
     */
    public function __invoke(Series $series, SeriesInvitationRequest $request)
    {
        $user = User::findOrFail(request('userID'));
        
        $series->invite($user);

        return to_route('series.edit', $series);
    }
}
