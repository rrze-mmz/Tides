<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Presenter extends BaseModel
{
    use HasFactory;

    public function getFullNameAttribute(): string
    {
        return "{$this->degree_title} {$this->first_name} {$this->last_name}";
    }

    /**
     * Clip relationship
     *
     * @return BelongsToMany
     */
    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class, 'clip_presenter')->withTimestamps();
    }
}
