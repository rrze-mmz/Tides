<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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
        if(static::whereSlug($slug = Str::of($value)->slug('-'))->exists())
        {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'clip_tag')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function addAsset($attributes = []): Model
    {
        return $this->assets()->create($attributes);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function incrementSlug($slug): mixed
    {
        $original = $slug;

        $count = 2;

        while (static::whereSlug($slug)->exists()) {

            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

    /**
     * Updates Clip poster Image on asset upload
     */
    public function updatePosterImage(): void
    {
        $this->posterImage = (Storage::disk('thumbnails')->exists($this->id.'_poster.png'))? $this->id.'_poster.png':null;

        $this->save();
    }
}
