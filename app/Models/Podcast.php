<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Podcast extends BaseModel
{
    use HasFactory;
    use RecordsActivity;
    use Searchable;

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }
}
