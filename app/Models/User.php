<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Searchable;
use App\Notifications\MailResetPasswordToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Searchable;
    use HasFactory;
    use Notifiable;
    use RecordsActivity;

    protected array $searchable = ['first_name', 'last_name', 'username', 'email'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Series relationship
     *
     * @return HasMany
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class, 'owner_id');
    }

    public function accessableSeries()
    {
        return Series::where('owner_id', $this->id)
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->orderBy('updated_at');
    }

    /**
     * Clip relationship
     *
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class, 'owner_id');
    }

    public function supervisedClips(): HasMany
    {
        return $this->hasMany(Clip::class, 'supervisor_id');
    }

    /**
     * Comments relationship
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'owner_id');
    }

    /**
     * Assign a role to the current use
     *
     * @param  string  $role
     * @return User
     */
    public function assignRole(string $role = ''): static
    {
        $this->roles()->sync([Role::where('name', $role)->first()->id]);

        return $this;
    }

    /**
     * Roles relationship
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Series Membership relationship
     *
     * @return BelongsToMany
     */
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'series_members')->withTimestamps();
    }

    /**
     * Check whether the current user has given role
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role = ''): bool
    {
        return (bool) $this->roles->contains('name', $role);
    }

    /**
     * Check whether the current user is a superadmin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check whether the current user is an admin or a superadmin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('superadmin');
    }

    /**
     * Check whether the current user is an editor
     *
     * @return bool
     */
    public function isModerator(): bool
    {
        return $this->hasRole('moderator');
    }

    /**
     * Check whether the current user is an assistant
     *
     * @return bool
     */
    public function isAssistant(): bool
    {
        return $this->hasRole('assistant');
    }

    /**
     * Check whether the current user is part of a series
     *
     * @param  Series  $series
     * @return bool
     */
    public function isMemberOf(Series $series): bool
    {
        return $this->memberships()->get()->contains($series);
    }

    /**
     * @return Builder|Series
     */
    public function getAllSeries(): Builder|Series
    {
        return Series::whereHas('clips', function ($q) {
            $q->where('supervisor_id', $this->id);
        })->orWhere('owner_id', $this->id);
    }

    /**
     * Fetch User settings
     *
     * @return BaseModel
     */
    public function settings(): BaseModel
    {
        return Setting::where('name', $this->username)->firstOrCreate(
            ['name' => $this->username],
            ['data' => config('settings.user')]
        );
    }

    /**
     * Resets user settings to default values
     *
     * @return BaseModel
     */
    public function resetSettings(): BaseModel
    {
        $setting = Setting::where('name', $this->username)->firstOrFail();

        //set user settings to default
        $setting->data = config('settings.user');
        $setting->save();

        return $this->settings();
    }

    /**
     * Override the default mail template
     * and send a custom password reset email
     * to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token, $this));
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'admin')->orWhere('name', 'superadmin');
        });
    }

    public function scopeModerators($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'moderator');
        });
    }

    /*
     * Only for test purposes and with use in tinker!
     *
     */
    public function resetPassword(): void
    {
        $this->password = Hash::make('12341234');
        $this->save();
    }
}
