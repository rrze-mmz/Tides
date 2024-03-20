<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatsLog extends StatsModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'logs';

    protected $primaryKey = 'log_id';

    public function asset(): BelongsTo
    {
        return $this->setConnection('pgsql')->belongsTo(Asset::class, 'resource_id');
    }
}
