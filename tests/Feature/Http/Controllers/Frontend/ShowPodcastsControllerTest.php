<?php

use function Pest\Laravel\get;

uses()->group('frontend');

it('shows all public podcasts to visitors', function () {
    get(route('frontend.podcasts.index'))->assertOk()
        ->assertViewIs('frontend.podcasts.index')
        ->assertViewHas(['podcasts']);
});

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
