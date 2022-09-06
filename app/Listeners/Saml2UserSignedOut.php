<?php

namespace App\Listeners;

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
        Event::listen('Slides\Saml2\Events\SignedOut', function (SignedOut $event) {
            Auth::logout();
            Session::save();
        });
    }
}
