<?php

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

uses()->group('unit');

it('has many clips', function () {
    $tag = Tag::factory()->create();

    expect($tag->clips())->toBeInstanceOf(BelongsToMany::class);
});
