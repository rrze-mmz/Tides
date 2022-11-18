<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    /**
     * User relationship
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeName($query, string $role)
    {
        return $query->where('name', $role);
    }
}
