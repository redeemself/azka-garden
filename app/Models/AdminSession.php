<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon|null $login_time
 * @property \Illuminate\Support\Carbon|null $logout_time
 * @property int $interface_id
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereLogoutTime($value)
 * @mixin \Eloquent
 */
class AdminSession extends Model
{
    protected $table = 'admin_sessions';
    public $timestamps = false;

    protected $fillable = [
        'admin_id', 'login_time', 'logout_time'
    ];

    protected $casts = [
        'login_time'  => 'datetime',
        'logout_time' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
