<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Presenter extends BaseModel
{
    use HasFactory;

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
