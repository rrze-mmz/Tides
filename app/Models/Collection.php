<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as LCollection;

class Collection extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    /*
     * Clips relationship
     *
     * @return BelongsToMany
     */
    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class);
    }

    /**
     * Add/remove clips for the given collection
     *
     * @param LCollection $ids
     * @return void
     */
    public function toggleClips(LCollection $ids): void
    {
        $this->clips()->toggle($ids);
    }
}
