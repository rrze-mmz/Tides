<?php

namespace App\Models\Stats;

use App\Models\Asset;
use App\Models\StatsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetViewLog extends StatsModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'logs';

    protected $primaryKey = 'log_id';

    public function asset(): BelongsTo
    {
        if (app()->environment('testing')) {
            return $this->belongsTo(Asset::class, 'resource_id');
        } else {
            return $this->setConnection('pgsql')->belongsTo(Asset::class, 'resource_id');
        }
    }
}
