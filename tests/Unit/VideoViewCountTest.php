<?php

use App\Models\Stats\AssetViewCount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses()->group('unit');

it('belongs to an asset', function () {
    expect(AssetViewCount::factory()->create()->asset())->toBeInstanceOf(BelongsTo::class);
});
