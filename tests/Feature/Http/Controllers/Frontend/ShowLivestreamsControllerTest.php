<?php

use function Pest\Laravel\get;

uses()->group('frontend');

it('gives back successful response for livestreams page', function () {
    get(route('frontend.livestreams.index'))->assertOk();
});

it('displays an info message if no active livestreams found', function () {
    get(route('frontend.livestreams.index'))->assertSee(__('livestream.frontend.no active livestream found'));
});
