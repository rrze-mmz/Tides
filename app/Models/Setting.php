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

    /**
     * Scope a query to only include opencast settings
     *
     * @param $query
     * @return mixed
     */
    public function scopeOpencast($query): mixed
    {
        return $query->where('name', 'opencast')->firstOrFail();
    }

    /**
     * Scope a query to only include portal settings
     *
     * @param $query
     * @return mixed
     */
    public function scopePortal($query): mixed
    {
        return $query->where('name', 'portal')->firstOrFail();
    }

    /**
     * Scope a query to only include streaming settings
     *
     * @param $query
     * @return mixed
     */
    public function scopeStreaming($query): mixed
    {
        return $query->where('name', 'streaming')->firstOrFail();
    }
}
