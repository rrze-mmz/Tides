<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application as ApplicationAlias;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserSubscriptionsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return ApplicationAlias|Factory|View
     */
    public function __invoke(Request $request)
    {
        return view('frontend.myPortal.subscriptions', ['series' => auth()->user()
                                                                ->subscriptions()
                                                                ->withLastPublicClip()->get()]);
    }
}
