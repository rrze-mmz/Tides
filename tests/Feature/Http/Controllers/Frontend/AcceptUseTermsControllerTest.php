<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\put;

uses()->group('frontend');

beforeEach(function () {
    signIn();
    $this->userSettings = auth()->user()->settings;
});

it('shows an error if checkbox is not checked', function () {
    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => ''])
        ->assertSessionHasErrors('accept_use_terms');
});

it('shows myPortal index page if use terms are accepted', function () {
    followingRedirects()
        ->put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on'])
        ->assertSee(__('myPortal.myPortal Settings'));
});

it('redirects to series subscriptions if a session key is set', function () {
    session(['redirect_back_to_subscribe' => 1]);

    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on'])
        ->assertRedirectToRoute('frontend.series.show', 1);
});

it('updates user settings when user accepts the use terms', function () {
    assertDatabaseHas('settings', [
        'name' => auth()->user()->username,
        'data' => json_encode(config('settings.user')), ]);

    put(route('frontend.acceptUseTerms'), ['accept_use_terms' => 'on'])
        ->assertSessionDoesntHaveErrors('accept_use_terms');

    $this->userSettings->refresh();

    assertDatabaseHas('settings', [
        'name' => auth()->user()->username,
        'data' => json_encode($this->userSettings->data), ]);
});
