<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Presenter extends BaseModel
{
    use HasFactory;

    public function getFullNameAttribute(): string
    {
        return ($this->academic_degree_id > 0)
            ? "{$this->academic_degree?->title} {$this->first_name} {$this->last_name}"
            : "{$this->first_name} {$this->last_name}";
    }

    /**
     * Series relationship
     *
     * @return MorphToMany
     */
    public function series(): MorphToMany
    {
        return $this->morphedByMany(Series::class, 'presentable')->withTimestamps();
    }

    /**
     * Clip relationship
     *
     * @return MorphToMany
     */
    public function clips(): MorphToMany
    {
        return $this->morphedByMany(Clip::class, 'presentable')->withTimestamps();
    }

    public function academic_degree(): BelongsTo
    {
        return $this->belongsTo(AcademicDegree::class);
    }
}
