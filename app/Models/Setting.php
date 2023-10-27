<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Setting extends BaseModel
{
    use HasFactory;

    public function scopeOpenAdminPortalApplications(): \Illuminate\Support\Collection
    {
        return DB::table('settings')
            ->select('name')
            ->whereJsonContains('data->admin_portal_application_status', ApplicationStatus::IN_PROGRESS->value)
            ->get();
    }

    /**
     * Scope a query to only include a single user settings
     */
    public function scopeUser($query, User $user): mixed
    {
        return $query->where('name', $user->username);
    }

    /**
     * Scope a query to only include opencast settings
     */
    public function scopeOpencast($query): mixed
    {
        return $query->where('name', 'opencast')->firstOrFail();
    }

    /**
     * Scope a query to only include portal settings
     */
    public function scopePortal($query): mixed
    {
        return $query->where('name', 'portal')->firstOrFail();
    }

    /**
     * Scope a query to only include streaming settings
     */
    public function scopeStreaming($query): mixed
    {
        return $query->where('name', 'streaming')->firstOrFail();
    }

    /**
     * Scope a query to only include OpenSearch settings
     */
    public function scopeOpenSearch($query): mixed
    {
        return $query->where('name', 'openSearch')->firstorFail();
    }

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
