<?php

use App\Enums\Role;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('backend');

it('denies access to simple user to create a channel', function () {
    get(route('channels.index'))->assertRedirect(route('login'));
    signInRole(Role::USER);
    get(route('channels.index'))->assertForbidden();
});

it('allows access to moderators to create a channel', function () {
    signInRole(Role::MODERATOR);

    get(route('channels.index'))->assertOk()
        ->assertViewIs('backend.channels.index')
        ->assertViewHas(['channels']);
});

it('shows a channel introduction text if channel is not activated yet', function () {
    signInRole(Role::MODERATOR);
    get(route('channels.index'))
        ->assertSee(__('myPortal.channels.introduction'));
});

it('asks to enable channels if moderator has no active channel', function () {
    signInRole(Role::MODERATOR);
    get(route('channels.index'))->assertSee(__('myPortal.channels.introduction'));
});

it('it validates url handle and channel title the form for channel activation', function () {
    signInRole(Role::MODERATOR);

    $attributes = [];
    post(route('channels.store'), $attributes)
        ->assertSessionHasErrors(['url_handle', 'name']);
});

it('creates a new channel when user is activates his channel', function () {
    $user = signInRole(Role::MODERATOR);
    $attributes = [
        'url_handle' => '@'.Str::before(auth()->user()->email, '@'),
        'name' => $user->getFullNameAttribute(),
        'description' => 'this is a test channel',
    ];
    post(route('channels.store'), $attributes)
        ->assertRedirectToRoute('channels.index');

    assertDatabaseHas('channels', [
        'url_handle' => $attributes['url_handle'],
    ]);
});

it('index page shows a list of channels if user has channels activated', function () {
    $user = signInRole(Role::MODERATOR);
    $attributes = [
        'url_handle' => '@'.Str::before(auth()->user()->email, '@'),
        'name' => $user->getFullNameAttribute(),
        'description' => 'this is a test channel',
    ];
    post(route('channels.store'), $attributes);

    $channelInfo = $user->channels()->first();
    get(route('channels.index'))->assertSee($channelInfo->name)->assertSee($channelInfo->description);
});

test('a users is not allowed activate another users channel', function () {
    $userA = signInRole(Role::MODERATOR);
    auth()->logout();
    signInRole(Role::MODERATOR);
    $attributes = [
        'url_handle' => '@'.Str::before($userA->email, '@'),
        'name' => $userA->getFullNameAttribute(),
        'description' => 'this is a test channel',
    ];

    post(route('channels.store'), $attributes)->assertForbidden();
    assertDatabaseMissing('channels', [
        'url_handle' => $attributes['url_handle'],
    ]);
});
