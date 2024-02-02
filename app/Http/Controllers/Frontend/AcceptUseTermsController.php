<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AcceptUseTermsController extends Controller
{
    /**
     * User accepts the use terms
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'accept_use_terms' => ['required', 'accepted'],
        ]);

        if ($validated['accept_use_terms'] === 'on') {
            $settings = auth()->user()->settings;
            $data = $settings->data;
            $data['accept_use_terms'] = true;
            $settings->data = $data;
            $settings->save();
        }

        // Check if we need to redirect back to subscription
        if (session()->has('redirect_back_to_subscribe')) {
            $seriesID = session('redirect_back_to_subscribe');

            // Redirect back to the original page where Livewire component can complete the subscription process
            return redirect()->route('frontend.series.show', $seriesID);
        }

        return to_route('frontend.userSettings.edit');
    }
}
