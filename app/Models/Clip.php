<?php

namespace App\Models;

use App\Enums\Content;
use App\Events\ClipDeleting;
use App\Models\Collection as TCollection;
use App\Models\Traits\Accessable;
use App\Models\Traits\Documentable;
use App\Models\Traits\Presentable;
use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use App\Models\Traits\Slugable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @method static first()
 * @method static find(int $int)
 */
class Clip extends BaseModel
{
    use Searchable;
    use Accessable;
    use Documentable;
    use Presentable;
    use Slugable;
    use RecordsActivity;

    //search columns for searchable trait
    protected array $searchable = ['title', 'description'];

    //hide clip password from elasticsearch index
    protected $hidden = ['password'];

    protected $dispatchesEvents = [
        'deleting' => ClipDeleting::class,
    ];

    protected $attributes = ['episode' => '1'];

    protected $casts = ['recording_date' => 'datetime:Y-m-d'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($clip) {
            $semester = Semester::find($clip->attributes['semester_id'])->acronym;
            $clip->setSlugAttribute($clip->episode.'-'.$clip->title.'-'.$semester);
        });

        static::updating(function ($clip) {
            $semester = Semester::find($clip->attributes['semester_id'])->acronym;
            $clip->setSlugAttribute($clip->episode.'-'.$clip->title.'-'.$semester);
        });
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => html_entity_decode(
                htmlspecialchars_decode(
                    html_entity_decode(html_entity_decode($value, ENT_NOQUOTES, 'UTF-8'))
                )
            )
        );
    }

    /**
     * Clip frontend link
     */
    public function path(): string
    {
        return "/clips/{$this->slug}";
    }

    /**
     * Clip backend link
     */
    public function adminPath(): string
    {
        return "/admin/clips/{$this->slug}";
    }

    /**
     * Clip routes should work with slug and with id to ensure backward compatibility
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $clip = $this->where('slug', $value)->first();
        if (is_null($clip)) {
            $clip = $this->where('id', (int) $value)->firstOrFail();
        }

        return $clip;
    }

    /**
     * Route key should be slugged instead of id
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * User relationship
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tags relationship
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clip_tag')->withTimestamps();
    }

    /**
     * Asset relationship
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function latestAsset(): HasOne
    {
        return $this->hasOne(Asset::class)->latestOfMany();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(TCollection::class);
    }

    /**
     * Series relationship
     */
    public function series(): BelongsTo
    {
        //a clip may not belong to a series
        return $this->belongsTo(Series::class)->withDefault();
    }

    /**
     *  A clip hat one semester
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     *  A clip belongs to an organization
     */
    public function organisation(): BelongsTo
    {
        return $this->BelongsTo(Organization::class, 'organization_id', 'org_id');
    }

    /**
     * A clip belongs to an image
     */
    public function image(): BelongsTo
    {
        return $this->BelongsTo(Image::class);
    }

    /**
     * A clip has one language
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * A clip has one context
     */
    public function context(): HasOne
    {
        return $this->hasOne(Context::class);
    }

    /**
     * Get all the clips's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * A clip has one format
     */
    public function format(): HasOne
    {
        return $this->hasOne(Format::class);
    }

    /**
     * A clip has one type
     */
    public function type(): HasOne
    {
        return $this->hasOne(Type::class);
    }

    /**
     * Adds an asset to clip
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
        if (Storage::disk('thumbnails')->exists($this->id.'_poster.png')) {
            $path = Storage::disk('thumbnails')->putFile(
                'clip_'.$this->id,
                new File(Storage::disk('thumbnails')->path($this->id.'_poster.png'))
            );

            $this->posterImage = $path;
            $this->save();
        } else {
            $this->posterImage = null;
        }
        $this->save();
    }

    /**
     * Add tags to clip
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
                return (int) $value->episode == (int) $this->episode - 1;
            })->first(),
            'nextClip' => $clipsCollection->filter(function ($value, $key) {
                return (int) $value->episode == (int) $this->episode + 1;
            })->first(),
        ]);
    }

    /**
     * Fetch all assets for a clip by type
     */
    public function getAssetsByType(Content $content): HasMany
    {
        return $this->assets()->where(function ($q) use ($content) {
            $q->where('type', $content());
        });
    }

    /**
     * Return caption asset for the clip
     */
    public function getCaptionAsset(): Asset|null
    {
        return $this->assets->filter(function ($asset) {
            return $asset->type == Content::CC();
        })->first();
    }

    /**
     *  Scope a query to only include public clips
     */
    public function scopePublic($query): mixed
    {
        return $query->where('is_public', 1);
    }

    public function scopeSingle($query): mixed
    {
        return $query->whereNull('series_id');
    }
}
