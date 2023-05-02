<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserApplicationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $settings = auth()->user()->settings->data;

        return view('frontend.myPortal.applications', [
            'settings' => $settings,
        ]);
    }
}
