<?php

use App\Models\Livestream;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function PHPUnit\Framework\assertInstanceOf;

uses()->group('unit');

beforeEach(function () {
    $this->livestream = Livestream::factory()->create();
});

it('belongs to sometimes to a clip', function () {
    assertInstanceOf(BelongsTo::class, $this->livestream->clip());
});

it('has an active scope', function () {
    expect(Livestream::active())->toBeInstanceOf(Builder::class);
});
