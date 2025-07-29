<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $admin_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminLog extends Model
{
    protected $table = 'admin_logs';
    public $timestamps = false;

    protected $fillable = [
        'admin_id', 'action', 'description', 'ip_address'
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
