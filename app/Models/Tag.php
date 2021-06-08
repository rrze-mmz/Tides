<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends BaseModel
{

    /**
     * Clip relationship
     *
     * @return BelongsToMany
     */
    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class, 'clip_tag')->withTimestamps();
    }
}
