<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends BaseModel
{
    use HasFactory;
    use RecordsActivity;
    use Searchable;

    protected array $searchable = ['description', 'file_name'];

    /**
     * Get the series for an image
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    /**
     * Get the clips for an image
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    public function presenters(): HasMany
    {
        return $this->hasMany(Presenter::class);
    }

    public function podcasts(): HasMany
    {
        return $this->hasMany(Podcast::class);
    }

    public function podcastsEpisodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }
}
