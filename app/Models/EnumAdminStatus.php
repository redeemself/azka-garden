<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminStatus> $adminStatuses
 * @property-read int|null $admin_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus whereValue($value)
 * @mixin \Eloquent
 */
class EnumAdminStatus extends Model
{
    protected $table = 'enum_admin_status';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function adminStatuses()
    {
        return $this->hasMany(AdminStatus::class, 'enum_admin_status_id');
    }
}
