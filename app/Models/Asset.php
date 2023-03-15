<?php

namespace App\Models;

use App\Enums\Content;
use App\Events\AssetDeleted;
use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Asset extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    protected $guarded = [];

    //this will update clips timestamp
    protected $touches = ['clip'];

    //remove asset from disk on delete
    protected $dispatchesEvents = [
        'deleted' => AssetDeleted::class,
    ];

    protected $dates = [
        'converted_for_downloading_at',
        'converted_for_streaming_at',
    ];

    /**
     * Return asset duration in hh:mm:ss format
     */
    public function durationToHours(): string
    {
        return gmdate('H:i:s', $this->duration);
    }

    /**
     *  Clip relationship
     */
    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }

    /**
     * Asset backend link
     */
    public function path(): string
    {
        return "/admin/assets/{$this->id}";
    }

    /**
     * Return assets download path
     */
    public function downloadPath(): string
    {
        return Storage::disk('videos')->path($this->path);
    }

    /**
     * Scope a query to only include video assets
     */
    public function scopeFormatVideo($query): mixed
    {
        return $query->where('type', Content::PRESENTER())
            ->orWhere('type', Content::PRESENTATION())
            ->orWhere('type', Content::COMPOSITE());
    }

    /**
     * Scope a query to only include audio assets
     */
    public function scopeFormatAudio($query): mixed
    {
        return $query->where('type', Content::AUDIO());
    }
}
