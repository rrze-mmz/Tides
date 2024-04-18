<?php

namespace App\Models\Stats;

use App\Models\Asset;
use App\Models\StatsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssetViewCount extends StatsModel
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'stats';

    public static function trendingClips(Carbon $date): Collection
    {
        return static::query()
            ->select('resourceid', DB::raw('sum(counter) as total_counter'))
            ->where('doa', '>=', $date)
            ->groupBy('resourceid')
            ->orderByDesc('total_counter')
            ->limit(10)
            ->get()
            ->map(function ($stat) {
                return [
                    'info' => $stat->asset->clip,
                    'counter' => $stat->total_counter,
                ];
            });
    }

    public function asset(): BelongsTo
    {
        if (app()->environment('testing')) {
            return $this->belongsTo(Asset::class, 'resourceid');
        } else {
            return $this->setConnection('pgsql')->belongsTo(Asset::class, 'resourceid');
        }
    }
}
