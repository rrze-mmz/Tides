<?php

namespace App\Models\Stats;

use App\Models\Asset;
use App\Models\StatsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetGeoCount extends StatsModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'geoloc';

    public function asset(): BelongsTo
    {
        return $this->setConnection('pgsql')->belongsTo(Asset::class, 'resourceid');
    }
}