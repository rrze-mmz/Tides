<?php

namespace App\Models;

use App\Enums\Role;
use App\Models\Traits\RecordsActivity;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
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

    /*
     * activates a channel for a specific moderator
     */
    /**
     * @throws Exception
     */
    public function activate(User $user): Channel|AuthorizationException|Exception
    {
        if (! $user->hasRole(Role::MODERATOR)) {
            throw new AuthorizationException('The user does not have a role: moderator');
        }

        if ($user->has('channels')) {
            throw new Exception('User has already a channel');
        }

        return Channel::create([
            'url_handle' => '@'.Str::before($user->email, '@'),
            'name' => $user->getFullNameAttribute(),
            'description' => '',
            'banner_url' => null,
        ]);
    }
}
