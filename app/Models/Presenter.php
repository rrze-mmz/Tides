<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Presenter extends BaseModel
{
    use Searchable;
    use HasFactory;
    use RecordsActivity;

    //search columns for searchable trait
    protected array $searchable = ['first_name', 'last_name', 'email', 'username'];

    public function getFullNameAttribute(): string
    {
        return ($this->academic_degree_id > 0)
            ? "{$this->academicDegree?->title} {$this->first_name} {$this->last_name}"
            : "{$this->first_name} {$this->last_name}";
    }

    /**
     * Series relationship
     */
    public function series(): MorphToMany
    {
        return $this->morphedByMany(Series::class, 'presentable')->withTimestamps();
    }

    /**
     * Clip relationship
     */
    public function clips(): MorphToMany
    {
        return $this->morphedByMany(Clip::class, 'presentable')->withTimestamps();
    }

    /*
     * A presenter has one  image
     */
    public function image(): BelongsTo
    {
        return $this->BelongsTo(Image::class);
    }

    public function clipsWithoutSeries(): Collection
    {
        return $this->clips->whereNull('series_id');
    }

    /**
     * Degree relationship
     */
    public function academicDegree(): BelongsTo
    {
        return $this->belongsTo(AcademicDegree::class);
    }
}
