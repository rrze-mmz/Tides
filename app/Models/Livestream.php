<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livestream extends BaseModel
{
    use HasFactory;
    use Searchable;

    protected array $searchable = ['name', 'app_name'];

    protected $casts = [
        'time_availability_start' => 'datetime',
        'time_availability_end' => 'datetime',
    ];

    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class)->withDefault();
    }

    public function scopeActive($query): mixed
    {
        return $query->where('active', 1);
    }
}
