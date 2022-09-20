<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseModel
{
    use HasFactory;

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    public function scopeOpencast($query)
    {
        return $query->where('name', 'opencast')->firstOrFail();
    }

    public function scopePortal($query)
    {
        return $query->where('name', 'portal')->firstOrFail();
    }
}
