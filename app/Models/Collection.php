<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    /*
     * A collection can have many clips
     */
    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class);
    }
}
