<?php

use App\Models\StatsLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses()->group('unit');

it('belongs to an asset', function () {
    expect(StatsLog::factory()->create()->asset())->toBeInstanceOf(BelongsTo::class);
});
