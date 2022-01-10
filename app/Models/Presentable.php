<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Presentable
{
    /**
     * Given model presenters relationship
     *
     * @return MorphToMany
     */
    public function presenters(): MorphToMany
    {
        return $this->morphToMany(Presenter::class, 'presentable')->withTimestamps();
    }

    /**
     * Add presenters to a given model
     *
     * @param Collection $presentersCollection
     * @return void
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
