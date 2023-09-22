<?php

use App\Models\Series;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses()->group('frontend');

test('user subscriptions is not for visitors available', function () {
    get(route('frontend.user.subscriptions'))->assertRedirect();
});

it('shows series subscriptions page for logged in user', function () {
    signIn();
    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);

    get(route('frontend.user.subscriptions'))
        ->assertOk()
        ->assertViewIs('frontend.myPortal.subscriptions')
        ->assertSee(__('myPortal.subscriptions.Your are subscribed to X Series', ['counter' => 0]));
});

it('lists user subscriptions', function () {
    signIn();
    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on']);
    auth()->user()->subscriptions()->attach(Series::factory(3)->create());

    get(route('frontend.user.subscriptions'))->assertSee(auth()->user()->subscriptions->first()->title);
});
