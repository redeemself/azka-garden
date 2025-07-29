<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $enum_admin_status_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $admins
 * @property-read int|null $admins_count
 * @property-read \App\Models\EnumAdminStatus $enumStatus
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus whereEnumAdminStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus whereId($value)
 * @mixin \Eloquent
 */
class AdminStatus extends Model
{
    protected $table = 'admin_statuses';
    protected $fillable = ['enum_admin_status_id'];

    public function enumStatus()
    {
        return $this->belongsTo(EnumAdminStatus::class, 'enum_admin_status_id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'status_id');
    }
}
