<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends BaseModel
{
    use HasFactory;

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }
}
