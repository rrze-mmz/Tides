<?php

namespace App\Models\Traits;

use App\Models\Presenter;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Presentable
{
    /**
     * Given model presenters relationship
     */
    public function presenters(): MorphToMany
    {
        return $this->morphToMany(Presenter::class, 'presentable')->withTimestamps();
    }

    /**
     * Add presenters to a given model
     */
    public function addPresenters(Collection $presentersCollection): void
    {
        if ($presentersCollection->isNotEmpty()) {
            $this->presenters()->sync($presentersCollection);
        } else {
            $this->presenters()->detach();
        }
    }
}
