<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Slides\Saml2\Events\SignedOut;

class Saml2UserSignedOut
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SignedOut  $event
     * @return void
     */
    public function handle(SignedOut $event): void
    {
        Auth::logout();
        Session::save();
    }
}
