<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Clip Eloquent relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }
}
