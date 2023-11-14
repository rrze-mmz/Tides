<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Series;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShowOrganizationsController extends Controller
{
    public function index(): View
    {
        $organizations = Organization::chairs()->orderBy('org_id')->get();

        return view('frontend.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $orgSeries = Series::whereHas('organization', function ($q) use ($organization) {
            $string = Str::substr($organization->orgno, 0, 2);
            $q->whereRaw('orgno  like (?)', ["{$string}%"]);
        })->isPublic()
            ->withLastPublicClip()
            ->hasClipsWithAssets()
            ->orderByDesc('updated_at')
            ->paginate(12);

        return view('frontend.organizations.show', compact(['organization', 'orgSeries']));
    }
}
