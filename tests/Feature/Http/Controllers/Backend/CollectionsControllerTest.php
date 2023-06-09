<?php

use App\Enums\Role;
use App\Models\Collection;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

uses()->group('backend')->beforeEach(function () {
    signInRole(Role::ADMIN);
});

it('collections index is forbidden for moderators', function () {
    auth()->logout();
    signInRole(Role::MODERATOR);

    get(route('collections.index'))->assertForbidden();
});

it('lists all available collections', function () {
    Collection::factory(2)->create();
    get(route('collections.index'))
        ->assertOk()
        ->assertViewHas(['collections'])
        ->assertSee(Collection::all()->first()->title);
});

it('renders a create form for new collection', function () {
    get(route('collections.create'))
        ->assertOk()
        ->assertSee('position')
        ->assertSee('title')
        ->assertSee('description')
        ->assertSee('is_public');
});

it('requires s position to create a collection', function () {
    post(route('collections.store'), [
        'position' => '',
        'title' => 'Test',
        'description' => 'test',
        'is_public' => false,
    ])->assertSessionHasErrors('position');
});

it('requires a title to create a collection', function () {
    post(route('collections.store'), [
        'position' => '1',
        'title' => '',
        'description' => 'test',
        'is_public' => false,
    ])->assertSessionHasErrors('title');
});

it('persists a collection to database', function () {
    post(route('collections.store'), [
        'position' => '1',
        'title' => 'Test',
        'description' => 'test',
        'is_public' => 'on',
    ])->assertRedirect();

    assertDatabaseHas('collections', [
        'position' => '1',
        'title' => 'Test',
        'description' => 'test',
        'is_public' => true,
    ]);
});

it('can edit collections', function () {
    get(route('collections.edit', Collection::factory()->create()))
        ->assertOk()
        ->assertViewHas('collection')
        ->assertSee('position')
        ->assertSee('title')
        ->assertSee('description')
        ->assertSee('is_public')
        ->assertViewIs('backend.collections.edit')
        ->assertSee('Toggle clips');
});

it('can update collections', function () {
    $collection = Collection::factory()->create();
    $attributes = $collection->toArray();
    $attributes['description'] = 'changed';

    patch(route('collections.update', $collection), $attributes)->assertRedirect();
    assertDatabaseHas('collections', [
        'id' => $collection->id,
        'description' => 'changed',
    ]);
});

it('requires a title to update a collection', function () {
    $collection = Collection::factory()->create();

    $attributes = $collection->toArray();
    $attributes['title'] = '';
    $attributes['description'] = 'changed';

    patch(route('collections.update', $collection), $attributes)->assertSessionHasErrors('title');
    assertDatabaseMissing('collections', [
        'id' => $collection->id,
        'description' => 'changed',
    ]);
});

it('can delete a collection', function () {
    $collection = Collection::factory()->create();
    delete(route('collections.destroy', $collection))->assertRedirect();
    assertDatabaseMissing('collections', [$collection->id]);
});
