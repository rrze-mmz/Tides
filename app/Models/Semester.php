<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Semester extends BaseModel
{
    use HasFactory;

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    public function scopeCurrent($query)
    {
        $query->where('start_date', '<=', Carbon::now())->where('stop_date', '>=', Carbon::now());
    }
}
