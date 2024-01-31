<?php

use function Pest\Laravel\get;

uses()->group('frontend');

it('allows channels index page to viewed by everyone', function () {
    get(route('frontend.channels.index'))
        ->assertOk()
        ->assertViewIs('frontend.channels.index')
        ->assertViewHas('channels');
});

it('has show channels page for each channel', function () {

})->todo();
