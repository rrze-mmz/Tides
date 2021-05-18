<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
     * @return HasMany
     */
    public function series(): HasMany
    {
        return $this->hasMany(Series::class, 'owner_id');
    }

    /**
     * @return HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class, 'owner_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'owner_id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * @param  string  $role
     * @return User
     */
    public function assignRole(string $role = ''): static
    {
        $role = tap(Role::firstOrCreate(['name' => $role]))->save();

        $this->roles()->sync($role->pluck('id'));

        return $this;
    }

    /**
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role = ''): bool
    {
        return ($this->roles->contains('name', $role)) ? true : false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
