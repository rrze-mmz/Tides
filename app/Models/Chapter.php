<?php

namespace App\Models;

use App\Events\ChapterDeleted;
use App\Models\Traits\RecordsActivity;
use App\Observers\ChapterObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(ChapterObserver::class)]
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

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    public function addClips(array $clipIDs): void
    {
        $oldClipIDs = $this->clips->pluck('id');
        Clip::whereIn('id', $clipIDs)->update(['chapter_id' => $this->id]);
        $this->series
            ->recordActivity('Added clips to chapter:'.$this->title, [
                'before' => $oldClipIDs,
                'after' => $clipIDs,
            ]);
    }

    public function removeClips(array $clipIDs): void
    {
        Clip::whereIn('id', $clipIDs)->update(['chapter_id' => null]);
        $newClipIDs = $this->clips->pluck('id');
        $this->series
            ->recordActivity('Remove clips from chapter:'.$this->title, [
                'before' => $clipIDs,
                'after' => $newClipIDs,
            ]);
    }
}
