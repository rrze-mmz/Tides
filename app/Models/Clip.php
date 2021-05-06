<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Clip extends Model {

    use HasFactory, Slugable;

    protected $guarded = [];

    protected $attributes = [
        'episode' => '1'
    ];

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

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clip_tag')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class)->withDefault();
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
     * Updates Clip poster Image on asset upload
     */
    public function updatePosterImage(): void
    {
        $this->posterImage = (Storage::disk('thumbnails')->exists($this->id . '_poster.png')) ? $this->id . '_poster.png' : null;

        $this->save();
    }

    /**
     * @param Collection $tagsCollection
     */
    public function addTags(Collection $tagsCollection): void
    {
        if ($tagsCollection->isNotEmpty())
        {
            $this->tags()->sync($tagsCollection->map(function ($tagName) {
                return tap(Tag::firstOrCreate(['name' => $tagName]))->save();
            })->pluck('id')
            );
        } else
        {
            $this->tags()->detach();
        }
    }
}
