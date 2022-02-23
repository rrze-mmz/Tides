<?php


namespace App\Models;

use App\Enums\Content;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @method static first()
 * @method static find(int $int)
 */
class Clip extends BaseModel
{
    use Accessable;
    use Presentable;
    use Slugable;

    protected $attributes = [
        'episode' => '1'
    ];
    protected $casts = [
        'recording_date' => 'datetime:Y-m-d'
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
     * Clip routes should work with slug and with id to ensure backward compatibility
     *
     * @param $value
     * @param $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('slug', $value)->orWhere('id', (int)$value)->firstOrFail();
    }

    /**
     * Route key should be slug instead of id
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * User relationship
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tags relationship
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clip_tag')->withTimestamps();
    }

    /**
     * Asset relationship
     *
     * @return HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function latestAsset(): HasOne
    {
        return $this->hasOne(Asset::class)->latestOfMany();
    }

    /**
     * Series relationship
     *
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        //a clip may not belong to a series
        return $this->belongsTo(Series::class)->withDefault();
    }

    /**
     * Comments relationship
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     *  A clip hat one semester
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     *  A clip has one organization
     *
     * @return HasOne
     */
    public function organisation(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    /**
     * A clip has one language
     *
     * @return HasOne
     */
    public function language(): HasOne
    {
        return $this->hasOne(Language::class);
    }

    /**
     * A clip has one context
     *
     * @return HasOne
     */
    public function context(): HasOne
    {
        return $this->hasOne(Context::class);
    }

    /**
     * A clip has one format
     *
     * @return HasOne
     */
    public function format(): HasOne
    {
        return $this->hasOne(Format::class);
    }

    /**
     * A clip has one type
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(Type::class);
    }

    /**
     * Clip smil file
     *
     * @return Asset|null
     */
    public function getCameraSmil(): Asset|null
    {
        return $this->assets()->firstWhere('original_file_name', '=', 'camera.smil');
    }

    /**
     * Adds an asset to clip
     *
     * @param array $attributes
     * @return Asset
     */
    public function addAsset(array $attributes = []): Asset
    {
        return $this->assets()->firstOrCreate($attributes);
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
     * Add tags to clip
     *
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
     * Return next and previous Models based on current Model episode attribute
     */
    public function previousNextClipCollection(): Collection
    {
        $clipsCollection = $this->series->clips()->orderBy('episode')->get();

        return collect([
            'previousClip' => $clipsCollection->filter(function ($value, $key) {
                return (int)$value->episode == (int)$this->episode - 1;
            })->first(),
            'nextClip'     => $clipsCollection->filter(function ($value, $key) {
                return (int)$value->episode == (int)$this->episode + 1;
            })->first()
        ]);
    }

    /**
     * Fetch all assets for a clip by type
     *
     * @param string $type
     * @return HasMany
     */
    public function getAssetsByType(string $type): HasMany
    {
        return $this->assets()->where(function ($q) use ($type) {
            $q->where('type', Content::from(Str::lower($type)));
        });
    }

    /**
     *  Scope a query to only include public clips
     *
     * @param $query
     * @return mixed
     */
    public function scopePublic($query): mixed
    {
        return $query->where('is_public', 1);
    }

    public function scopeTypeAssets($query, string $type): mixed
    {
        return $query->whereHas('assets', function ($q) use ($type) {
            $q->where('type', Content::from(Str::lower($type)));
        });
    }
}
