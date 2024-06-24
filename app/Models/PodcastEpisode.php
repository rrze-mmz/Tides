<?php

namespace App\Models;

use App\Models\Traits\Presentable;
use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastEpisode extends BaseModel
{
    use HasFactory;
    use Presentable;
    use RecordsActivity;
    use Searchable;
    use Slugable;

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
