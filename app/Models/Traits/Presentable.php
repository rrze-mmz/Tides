<?php

namespace App\Models\Traits;

use App\Models\Presenter;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Presentable
{
    /**
     * Add presenters to a given model
     */
    public function addPresenters(Collection $presentersCollection): void
    {
        if ($presentersCollection->isEmpty()) {
            $this->presenters()->detach();
            $this->recordActivity('All Presenters removed.', [
                'before' => $this->presenters->pluck('last_name')->flatten(),
                'after' => []]);

            return;
        }
        $existingPresenters = $this->presenters->pluck('id')->sort()->values();
        $newPresenters = $presentersCollection->sort()->values();
        if (! $existingPresenters->diff($newPresenters)->isEmpty() ||
            ! $newPresenters->diff($existingPresenters)->isEmpty()) {
            $this->presenters()->sync($presentersCollection);
            $this->recordActivity('ACL changed! ', [
                'before' => $existingPresenters->map(function ($value) {
                    return Presenter::find($value)->getFullNameAttribute();
                })->toArray(),
                'after' => $newPresenters->map(function ($value) {
                    return Presenter::find($value)->getFullNameAttribute();
                })->toArray(),
            ]);
        }
    }

    /**
     * Given model presenters relationship
     */
    public function presenters(): MorphToMany
    {
        return $this->morphToMany(Presenter::class, 'presentable')->withTimestamps();
    }
}
