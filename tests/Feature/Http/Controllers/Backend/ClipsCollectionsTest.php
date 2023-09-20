<?php

use App\Enums\Role;
use App\Models\Clip;
use App\Models\Collection;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;

uses()->group('backend');

it('can toggle clips to a collection', function () {
    signInRole(Role::ADMIN);

    $attributes = [
        'ids' => Clip::factory(2)->create()->pluck('id')->flatten()->all(),
    ];

    post(route('collections.toggleClips', $collection = Collection::factory()->create()), $attributes)
        ->assertRedirect();

    assertDatabaseHas('clip_collection', [
        'clip_id' => Clip::all()->first()->id,
        'collection_id' => $collection->id,
    ]);
    post(route('collections.toggleClips', $collection), $attributes)
        ->assertRedirect();

    assertDatabaseMissing('clip_collection', [
        'clip_id' => Clip::all()->first()->id,
        'collection_id' => $collection->id,
    ]);
});
