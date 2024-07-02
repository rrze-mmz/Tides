<?php

use App\Models\PodcastEpisode;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses()->group('unit');

beforeEach(function () {
    $this->podcastEpisode = PodcastEpisode::factory()->create();
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('belongs to a podcast', function () {
    expect($this->podcastEpisode->podcast())->toBeInstanceOf(BelongsTo::class);
});

it('belongs to an image with the attribute of podcast cover', function () {
    expect($this->podcastEpisode->cover())->toBeInstanceOf(BelongsTo::class);
});
