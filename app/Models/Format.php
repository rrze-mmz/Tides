<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Format extends BaseModel
{
    use HasFactory;

    /**
     * Clips relationship
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }
}
