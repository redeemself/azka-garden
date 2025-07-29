<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $role_id
 * @property int $status_id
 * @property string|null $specialization
 * @property string|null $github_profile
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeveloperLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeveloperPermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\DevRole $role
 * @property-read \App\Models\DevStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereGithubProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUsername($value)
 * @mixin \Eloquent
 */
class Developer extends Authenticatable
{
    use Notifiable;

    protected $table = 'developers';

    protected $fillable = [
        'name', 'username', 'password', 'email',
        'role_id', 'status_id', 'specialization',
        'github_profile', 'last_login'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(DevRole::class);
    }

    public function status()
    {
        return $this->belongsTo(DevStatus::class);
    }

    public function permissions()
    {
        return $this->hasMany(DeveloperPermission::class);
    }

    public function logs()
    {
        return $this->hasMany(DeveloperLog::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    // Tambahkan method ini:
    public function isDeveloper(): bool
    {
        // Sesuaikan kondisi ini sesuai struktur Role/Enum di Developer
        return $this->role && strtoupper($this->role->value ?? '') === 'DEVELOPER';
    }
}
