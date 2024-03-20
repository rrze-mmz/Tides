<?php

use App\Models\StatsCounter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses()->group('unit');

it('belongs to an asset', function () {
    expect(StatsCounter::factory()->create()->asset())->toBeInstanceOf(BelongsTo::class);
});
