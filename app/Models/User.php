<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Model
 *
 * Last updated: 2025-07-30 07:32:44
 * Updated by: mulyadafa
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property string|null $plain_password
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property int $interface_id
 * @property string|null $profile_photo_path
 * @property-read string $avatar
 * @property-read Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plain_password',
        'phone',
        'last_login',
        'interface_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // opsional: jika ingin plain_password tidak tampil di toArray() / API
        // 'plain_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Set the password attribute with automatic hashing if needed.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        if (!empty($password) && Hash::needsRehash($password)) {
            $this->attributes['password'] = Hash::make($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    /**
     * Get the roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Get the addresses for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the orders for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * Get the cart items for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/profiles/' . $this->profile_photo_path);
        }
        return asset('images/default-user.png');
    }

    /**
     * Check if user has one or more roles.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        $roleValues = $this->roles->map(fn($role) => Str::upper($role->name))->toArray();

        if (is_array($roles)) {
            $roles = array_map('Str::upper', $roles);
            return count(array_intersect($roleValues, $roles)) > 0;
        }

        return in_array(Str::upper((string) $roles), $roleValues);
    }
}
