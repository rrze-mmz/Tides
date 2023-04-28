<?php

use App\Enums\Role;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('frontend');

it('has a route showing all , applications statuses', function () {
    signInRole(Role::MEMBER);
    acceptUseTerms();
    acceptAdminPortalUseTerms();

    get(route('frontend.user.applications'))->assertOk();
});

it('has a applications view for user applications', function () {
    $user = signInRole(Role::MEMBER);
    acceptUseTerms();
    put(route('frontend.admin.portal.accept.use.terms'), ['accept_use_terms' => 'on']);

    get(route('frontend.user.applications'))->assertViewIs('frontend.myPortal.applications');
});
