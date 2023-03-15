<?php

namespace App\Models\Traits;

/*
 *  Trait copied from here
 *  https://www.twilio.com/blog/build-live-search-box-laravel-livewire-mysql
 */

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    protected function scopeSearch(Builder $query, string $term): Builder
    {
        $term = $this->lowerCaseTerm($term);

        $columns = collect($this->searchable);

        $columns->each(function ($column) use ($term, $query) {
            $query->orWhereRaw('lower('.$column.") like ('%".$term."%')");
        });

        return $query;
    }

    private function lowerCaseTerm($term): array|string
    {
        $term = str_replace(['-', '+', '<', '>', '(', ')', '~'], '', $term);

        return trim(str($term)->lower());
    }
}
