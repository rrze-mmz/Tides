<?php

use function Pest\Laravel\get;

it('gives back successful response for livestreams page', function () {
    get(route('frontend.livestreams.index'))->assertOk();
});

it('displays an info message if no active livestreams found', function () {
    get(route('frontend.livestreams.index'))->assertSee('no active livestreams found');
});
