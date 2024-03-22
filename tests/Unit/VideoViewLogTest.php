<?php

use App\Models\Stats\AssetViewLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

uses()->group('unit');

it('belongs to an asset', function () {
    expect(AssetViewLog::factory()->create()->asset())->toBeInstanceOf(BelongsTo::class);
});
