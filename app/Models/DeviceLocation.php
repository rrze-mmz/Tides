<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceLocation extends Model
{
    use HasFactory;
    use Searchable;

    protected array $searchable = ['name'];

    /**
     * Devices relationship
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
