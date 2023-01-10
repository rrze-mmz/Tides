<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ShowOrganizationsController extends Controller
{
    public function index(): View
    {
        $organizations = Organization::chairs()->whereHas('series', function (Builder $query) {
            $query->isPublic()->hasClipsWithAssets(); //series must be public accessable and must contain video assets
        })->get();

        return view('frontend.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
//        $orgSeries = $organization->with(['series' => function ($query) {
//            $query->isPublic();
//        }])->orderByDesc('updated_at')->paginate(10);
        $orgSeries = $organization->series()->isPublic()->hasClipsWithAssets()->orderByDesc('updated_at')->paginate(10);

        return view('frontend.organizations.show', compact(['organization', 'orgSeries']));
    }
}
