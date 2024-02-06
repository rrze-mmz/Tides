<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    use Searchable;

    protected array $searchable = ['name'];

    /**
     * User relationship
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
