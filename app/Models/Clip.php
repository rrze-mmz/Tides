<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Clip extends Model
{
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
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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
     * Updates clip poster image on asset upload
     */
    public function updatePosterImage(): void
    {
        $this->posterImage =
            (Storage::disk('thumbnails')->exists($this->id . '_poster.png')) ? $this->id . '_poster.png' : null;

        $this->save();
    }

    /**
     * @param Collection $tagsCollection
     */
    public function addTags(Collection $tagsCollection): void
    {
        /*
         * Check for tags collection from post request.
         * The closure returns a tag model, where the model is either selected or created.
         * The tag model is synchronized with the clip tags.
         * In case the collection is empty assumed that clip has no tags and delete them
         */
        if ($tagsCollection->isNotEmpty()) {
            $this->tags()->sync($tagsCollection->map(function ($tagName) {
                return tap(Tag::firstOrCreate(['name' => $tagName]))->save();
            })->pluck('id'));
        } else {
            $this->tags()->detach();
        }
    }

    /*
     * Return next and previoud Models based on current Model
     */
    public function previousNextClipCollection(): Collection
    {

        $clipsCollection = $this->series->clips()->orderBy('episode')->get();

        return collect([
            'previous' => $clipsCollection->filter(function ($value, $key) {
                return (int)$value->episode == (int)$this->episode - 1;
            })->first(),
            'next'     => $clipsCollection->filter(function ($value, $key) {
                return (int)$value->episode == (int) $this->episode + 1;
            })->first()
        ]);
    }
}
