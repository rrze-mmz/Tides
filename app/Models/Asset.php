<?php

namespace App\Models;

use App\Events\AssetDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    //this will update clips timestamp
    protected $touches = ['clip'];

    //fire an  event on delete
    protected $dispatchesEvents = [
        'deleted' => AssetDeleted::class
    ];

    /**
     * Clip Eloquent relationship
     *
     * @return BelongsTo
     */
    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return "/admin/assets/{$this->id}";
    }
}
