<?php


namespace App\Models;

use App\Events\AssetDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends BaseModel
{

    use HasFactory;

    protected $guarded = [];

    //this will update clips timestamp
    protected $touches = ['clip'];

    //fire an  event on delete
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
}
