<?php

namespace App\Models;

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
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
