<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses()->group('frontend');

it('is forbidden for a use with saml role student to access admin portal use terms page', function () {
   //sign in a student
    actingAs(User::factory()->create());

    acceptUseTerms();

    get(route('frontend.admin.portal.use.terms'))->assertForbidden();

});
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
