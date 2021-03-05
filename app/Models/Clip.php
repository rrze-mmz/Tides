<?php

namespace App\Models;

use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Clip extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Clip frontend link
     *
     * @return string
     */
    public function path(): string
    {
        return "/clips/{$this->slug}";
    }

    /**
     * Clip backend link
     *
     * @return string
     */
    public function adminPath(): string
    {
        return "/admin/clips/{$this->slug}";
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param $value
     */
    public function setSlugAttribute($value):void
    {
        if(static::whereSlug($slug = Str::slug($value))->exists())
        {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * @param $uploadedFile
     * @return Model
     */
    public function addAsset($uploadedFile): Model
    {
        return $this->assets()->create(compact('uploadedFile'));
    }

    /**
     * @param $slug
     * @return mixed|string
     */
    public function incrementSlug($slug)
    {
        $original = $slug;

        $count = 2;

        while (static::whereSlug($slug)->exists()) {

            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

}
