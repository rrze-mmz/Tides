<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AcceptUseTermsController extends Controller
{
    /**
     * User accepts the use terms
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'accept_use_terms' => ['required', 'accepted'],
        ]);

        if ($validated['accept_use_terms'] === 'on') {
            $settings = auth()->user()->settings();
            $data = $settings->data;
            $data['accept_use_terms'] = true;
            $settings->data = $data;
            $settings->save();
        }

        return to_route('frontend.userSettings.edit');
    }
}
