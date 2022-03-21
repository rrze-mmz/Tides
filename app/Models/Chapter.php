<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends BaseModel
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    /**
     * @param array $clipIDs
     * @return int
     */
    public function addClips(array $clipIDs): int
    {
        return Clip::whereIn('id', $clipIDs)->update(['chapter_id' => $this->id]);
    }
}
