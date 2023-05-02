<?php

use App\Enums\Role;
use App\Mail\NewApplicationForAdminPortal;
use Illuminate\Support\Facades\Mail;

uses()->group('frontend');

it('send an email to tides support mail address if a member apply for admin portal', function () {
    Mail::fake();

    signInRole(Role::MEMBER);
    acceptUseTerms();
    acceptAdminPortalUseTerms();

    Mail::assertSent(NewApplicationForAdminPortal::class);
});
