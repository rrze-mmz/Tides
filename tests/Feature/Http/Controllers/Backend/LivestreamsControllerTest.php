<?php

use App\Enums\Role;
use App\Models\Livestream;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses()->group('backend');

it('redirects visitors to login page', function () {
    get(route('livestreams.index'))->assertRedirectToRoute('login');
});

it('denies access to users with role student and moderator', function () {
    signInRole(Role::STUDENT);
    get(route('livestreams.index'))->assertForbidden();
    auth()->logout();

    signInRole(Role::MODERATOR);
    get(route('livestreams.index'))->assertForbidden();
    auth()->logout();
});

it('lists all livestreams to portal admins', function () {
    signInRole(Role::ASSISTANT);
    $livestream = Livestream::factory()->create();
    get(route('livestreams.index'))->assertOk()->assertSee($livestream->name);
});

it('has options for edit and delete a livestream in index table', function () {
    signInRole(Role::ASSISTANT);
    $livestream = Livestream::factory()->create();
    get(route('livestreams.index'))->assertSee(route('livestreams.edit', $livestream->id))
        ->assertSee(route('livestreams.destroy', $livestream->id));
});

it('rejects store requests from assistants', function () {
    signInRole(Role::ASSISTANT);
    $attributes = Livestream::factory()->raw();

    post(route('livestreams.store'), $attributes);

    assertDatabaseMissing('livestreams', $attributes);
});
it('validates a request to store a new livestream room in the database', function () {
    signInRole(Role::ADMIN);
    $attributes = [];

    post(route('livestreams.store'), $attributes)->assertSessionHasErrors();
});

it('stores a new livestream room in the database', function () {
    signInRole(Role::ADMIN);
    $attributes = Livestream::factory()->raw();
    post(route('livestreams.store'), $attributes);

    assertDatabaseHas('livestreams', ['name' => $attributes['name']]);
});

it('validates editing an existing livestream room', function () {
    signInRole(Role::ADMIN);

    put(route('livestreams.update', Livestream::factory()->create()), [])->assertSessionHasErrors();
});

it('updates an existing livestream room', function () {
    signInRole(Role::ADMIN);
    $attributes = [
        'name' => 'An edited room',
        'opencast_location_name' => 'edited-room',
        'app_name' => 'edited-room',
        'content_path' => 'edited-room/path',
        'has_transcoder' => false,
    ];
    put(route('livestreams.update', Livestream::factory()->create()), $attributes);

    assertDatabaseHas('livestreams', $attributes);
});

it('deletes an existing livestream room', function () {
    signInRole(Role::ADMIN);
    $livestream = Livestream::factory()->create();

    delete(route('livestreams.destroy', $livestream));

    assertDatabaseMissing('livestreams', $livestream->toArray());
});
