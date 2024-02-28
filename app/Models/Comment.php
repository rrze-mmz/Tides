<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends BaseModel
{
    /**
     * Get the parent commentable model (series or clip).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * User relationship
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBackend(Builder $query): void
    {
        $query->where('type', 'backend');
    }

    public function scopeFrontend(Builder $query): void
    {
        $query->where('type', 'frontend');
    }
}
