<?php

namespace App\Models;

use App\Models\Traits\Presentable;
use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Podcast extends BaseModel
{
    use HasFactory;
    use Presentable;
    use RecordsActivity;
    use Searchable;

    protected array $searchable = ['title', 'description'];

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }
}
