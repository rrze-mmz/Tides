<?php

use App\Models\Format;
use Illuminate\Database\Eloquent\Relations\HasMany;

uses()->group('unit');

beforeEach(function () {
    // TODO: Change the autogenerated stub
    $this->format = Format::factory()->create();
});

it('has many clips', function () {
    expect($this->format->clips())->toBeInstanceOf(HasMany::class);
});
