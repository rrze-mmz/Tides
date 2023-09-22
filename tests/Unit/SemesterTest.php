<?php

use App\Models\Semester;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

uses()->group('unit');

it('has many clips', function () {
    expect(Semester::find(1)->clips())->toBeInstanceOf(HasMany::class);
});

it('has a current semester scope', function () {
    expect(Semester::current())->toBeInstanceOf(Builder::class);
});
