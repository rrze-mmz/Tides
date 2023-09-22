<?php

use App\Enums\Role;
use App\Models\Series;
use Facades\Tests\Setup\SeriesFactory;

use function Pest\Laravel\put;

test('an image id must selected to update series image', function () {
    signInRole(Role::MODERATOR);
    $series = Series::factory()->create();

    put(route('update.series.image', $series), ['imageID' => ''])
        ->assertSessionHasErrors('imageID');
});

it('can update images for series and for all series clips', function () {
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))
        ->withClips(3)
        ->create();

    expect($series->image_id)->toEqual(2);
    expect($series->clips()->first()->image_id)->toEqual(2);

    put(route('update.series.image', $series), ['imageID' => 1, 'assignClips' => 'on']);
    $series->refresh();

    expect($series->image_id)->toEqual(1);
    expect($series->clips()->first()->image_id)->toEqual(1);
});

it('can update series image', function () {
    signInRole(Role::MODERATOR);
    $series = Series::factory()->create();

    put(route('update.series.image', $series), ['imageID' => 1])
        ->assertRedirectToRoute('series.edit', $series);

    $series->refresh();
    expect($series->image_id)->toEqual(1);
});
