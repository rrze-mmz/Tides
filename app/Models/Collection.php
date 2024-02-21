<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Observers\CollectionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as LCollection;

#[ObservedBy(CollectionObserver::class)]
class Collection extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    /*
     * Clips relationship
     *
     * @return BelongsToMany
     */
    /**
     * Add/remove clips for the given collection
     */
    public function toggleClips(LCollection $ids): void
    {
        $this->clips()->toggle($ids);
    }

    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class);
    }
}
