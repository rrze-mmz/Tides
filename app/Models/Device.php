<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends BaseModel
{
    use HasFactory;
    use Searchable;

    protected array $searchable = [
        'name', 'description', 'comment', 'opencast_device_name', 'url', 'telephone_number'
    ];

    /**
     * Location relationship
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(DeviceLocation::class, 'location_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
