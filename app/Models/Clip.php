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
use App\Observers\ClipObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
#[ObservedBy(ClipObserver::class)]
class Clip extends BaseModel
{
    use Accessable;
    use Documentable;
    use Presentable;
    use RecordsActivity;
    use Searchable;
    use Slugable;

    protected $with = ['acls'];

    // Update series timestamps on clip update
    protected $touches = ['series'];

    // search columns for searchable trait
    protected array $searchable = ['title', 'description'];

    // hide clip password from OpenSearch index
    protected $hidden = ['password'];

    protected $dispatchesEvents = [
        'deleting' => ClipDeleting::class,
    ];

    protected $attributes = ['episode' => '1'];

    protected $casts = [
        'recording_date' => 'datetime:Y-m-d',
        'time_availability_start' => 'datetime',
        'time_availability_end' => 'datetime',
    ];

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

    public function latestAsset(): HasOne
    {
        return $this->hasOne(Asset::class)->ofMany('duration', 'max');
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
    public function organization(): BelongsTo
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
     * Get all the clips's comments.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * A clip belongs to context
     */
    public function context(): BelongsTo
    {
        return $this->BelongsTo(Context::class);
    }

    /**
     * A clip belongs to format
     */
    public function format(): BelongsTo
    {
        return $this->BelongsTo(Format::class);
    }

    /**
     * A clip belongs to type
     */
    public function type(): BelongsTo
    {
        return $this->BelongsTo(Type::class);
    }

    /**
     * Adds an asset to clip
     */
    public function addAsset(array $attributes = []): Asset
    {
        return $this->assets()->firstOrCreate($attributes);
    }

    /**
     * Asset relationship
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
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
     * Clip frontend link
     */
    public function path(): string
    {
        return "/clips/{$this->slug}";
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

    /**
     * Tags relationship
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clip_tag')->withTimestamps();
    }

    public function livestream(): HasOne
    {
        return $this->hasOne(Livestream::class);
    }

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

    /*
     * Return next and previous Models based on current Model episode attribute
     */

    /**
     * Return caption asset for the clip
     */
    public function getCaptionAsset(): ?Asset
    {
        return $this->assets->filter(function ($asset) {
            return $asset->type == Content::CC() && ! $asset->is_deleted;
        })->first();
    }

    // Function to calculate total views for all assets' stats
    public function views(): int
    {
        return $this->assets->load('statsCounter')->sum(function ($asset) {
            // Sum the views for each asset from its multiple statsCounter entries
            return $asset->statsCounter->sum('counter'); // Assuming 'views' is the column where views are stored
        });
    }

    /**
     *  Scope a query to only include public clips
     */
    public function scopePublic($query): mixed
    {
        return $query->where('is_public', 1);
    }

    /**
     *  Scope a query to only include clips without series
     */
    public function scopeSingle($query): mixed
    {
        return $query->whereNull('series_id');
    }

    /**
     *  Scope a query to only include the semester name of the clip
     */
    public function scopeWithSemester($query)
    {
        return $query->addSelect(
            [
                'semester' => Semester::select('name')
                    ->whereColumn('id', 'clips.semester_id')
                    ->take(1),
            ]
        );
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
}
