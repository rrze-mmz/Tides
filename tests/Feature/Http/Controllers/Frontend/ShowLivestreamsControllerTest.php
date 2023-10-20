<?php

use function Pest\Laravel\get;

it('gives back successful response for livestreams page', function () {
    get(route('live-now'))->assertOk();
});

it('displays an info message if no active livestreams found', function () {
    get(route('live-now'))->assertSee('No active livestreams found');
});
