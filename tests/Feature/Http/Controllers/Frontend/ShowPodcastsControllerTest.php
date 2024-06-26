<?php

use App\Models\Podcast;

use function Pest\Laravel\get;

uses()->group('frontend');

it('shows all public podcasts to visitors', function () {
    get(route('frontend.podcasts.index'))->assertOk()
        ->assertViewIs('frontend.podcasts.index')
        ->assertViewHas(['podcasts']);
});

it('shows no podcasts found if podcasts list is empty', function () {
    get(route('frontend.podcasts.index'))->assertSee('No podcasts found or published');
});

it('lists all published podcasts with episodes to visitors', function () {
    $podcast = Podcast::factory()->create();

    get(route('frontend.podcasts.index'))->assertDontSee($podcast->title);
});
