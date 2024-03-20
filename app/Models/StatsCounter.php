<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatsCounter extends StatsModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'stats';

    public function asset(): BelongsTo
    {
        return $this->setConnection('pgsql')->belongsTo(Asset::class, 'resourceid');
    }
}
