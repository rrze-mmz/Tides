<?php

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

uses()->group('unit');

beforeEach(function () {
    $this->podcast = Podcast::factory()->create();
});
test('example', function () {
    expect(true)->toBeTrue();
});

it('has many podcast episodes', function () {
    expect($this->podcast->episodes())->toBeInstanceOf(HasMany::class);
});

it('belogns to an image with the attribute of podcast cover', function () {
    expect($this->podcast->cover())->toBeInstanceOf(BelongsTo::class);
});
