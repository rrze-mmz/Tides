<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Format extends Model
{
    use HasFactory;

    /**
     * Clips relationship
     *
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }
}
