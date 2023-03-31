<?php

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;

uses()->group('unit');

beforeEach(function () {
    $this->image = Image::factory()->create();
});

it('has many series', function () {
    $this->assertInstanceOf(HasMany::class, $this->image->series());
});

it('has many clips', function () {
    $this->assertInstanceOf(HasMany::class, $this->image->clips());
});

it('has many presenters', function () {
    $this->assertInstanceOf(HasMany::class, $this->image->presenters());
});
