<?php

namespace App\Models\Traits;

/*
 *  Trait copied from here
 *  https://www.twilio.com/blog/build-live-search-box-laravel-livewire-mysql
 */

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    private function lowerCaseTerm($term)
    {
        if ($term == "") {
            return $term;
        }

        $reservedSymbols = ['-', '+', '<', '>', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        trim(str($term)->lower());

        return $term;
    }

    protected function scopeSearch(Builder $query, string $term): Builder
    {
        $term = $this->lowerCaseTerm($term);
        $columns = collect($this->searchable);

        $columns->each(function ($column) use ($term, $query) {
            $query->orWhereRaw("lower(" . $column . ") like ('%" . $term . "%')");
        });

        return $query;
    }
}
