<?php

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

uses()->group('unit');

beforeEach(function () {
    $this->podcast = Podcast::factory()->create();
});

it('belongs to an user', function () {
    expect($this->podcast->owner())->toBeInstanceOf(BelongsTo::class);
});

it('has many podcasts episodes', function () {
    expect($this->podcast->episodes())->toBeInstanceOf(HasMany::class);
});

it('has many comments', function () {
    expect($this->podcast->comments())->toBeInstanceOf(MorphMany::class);
});

it('belogns to an image with the attribute of podcasts cover', function () {
    expect($this->podcast->cover())->toBeInstanceOf(BelongsTo::class);
});
