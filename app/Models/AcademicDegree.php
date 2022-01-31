<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicDegree extends BaseModel
{
    use HasFactory;

    public function presenters(): HasMany
    {
        return $this->hasMany(Presenter::class);
    }
}
