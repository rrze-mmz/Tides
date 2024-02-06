<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Channel extends BaseModel
{
    use HasFactory;
    use RecordsActivity;

    /**
     * Route key should be the url handel @email without the @ instead of id
     */
    public function getRouteKeyName()
    {
        return 'url_handle';
    }

    /**
     * User relationship
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
