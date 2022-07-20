<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends BaseModel
{
    use Searchable;
    use HasFactory;

    //search columns for searchable trait
    protected array $searchable = ['name'];

    protected $primaryKey = 'org_id';

    /**
     * Get the clip clips for an organization unit
     *
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }

    /**
     * Get the series for an organization unit
     *
     * @return HasMany
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }
}
