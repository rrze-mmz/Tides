<?php

namespace App\Models;

use App\Events\ChapterDeleted;
use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    /**
     * On chapter delete set chapter clips chapter_id to null
     */
    protected $dispatchesEvents = [
        'deleted' => ChapterDeleted::class,
    ];

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
     * @param  array  $clipIDs
     * @return int
     */
    public function addClips(array $clipIDs): int
    {
        return Clip::whereIn('id', $clipIDs)->update(['chapter_id' => $this->id]);
    }

    /**
     * @param  array  $clipIDs
     * @return int
     */
    public function removeClips(array $clipIDs): int
    {
        return Clip::whereIn('id', $clipIDs)->update(['chapter_id' => null]);
    }
}
