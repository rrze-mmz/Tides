<?php


namespace App\Models;

use Illuminate\Support\Str;

trait Slugable
{
    /**
     * Set the slug to another value if another one with the same value exists
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        if (self::whereSlug($slug = Str::of($value)->slug('-'))
            ->Where('id', '!=', self::getKey())->exists()) {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * Adds a counter if there are two objects with the same slug
     *
     * @param $slug
     * @return mixed
     */
    protected function incrementSlug($slug): mixed
    {
        $original = $slug;

        $count = 2;

        while (self::whereSlug($slug)->exists()) {
            $slug = "{$original}-".$count++;
        }

        return $slug;
    }
}
