<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class AdminPortalUseTermsController extends Controller
{
    public function terms()
    {
        //students not allowed to apply for moderator role
        if (auth()->user()->saml_role === 'student') {
            abort(403);
        }

        return view('frontend.myPortal.adminPortalUseTerms');
    }
}
