<?php


namespace App\Models;

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
        'deleted' => AssetDeleted::class
    ];
    protected $dates = [
        'converted_for_downloading_at',
        'converted_for_streaming_at',
    ];

    /**
     * Return asset duration in hh:mm:ss format
     *
     * @return string
     */
    public function durationToHours(): string
    {
        return gmdate("H:i:s", $this->duration);
    }

    /**
     *  Clip relationship
     *
     * @return BelongsTo
     */
    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }

    /**
     * Asset backend link
     *
     * @return string
     */
    public function path(): string
    {
        return "/admin/assets/{$this->id}";
    }

    /**
     * Return assets download path
     *
     * @return string
     */
    public function downloadPath(): string
    {
        return Storage::disk('videos')->path($this->path);
    }
}
