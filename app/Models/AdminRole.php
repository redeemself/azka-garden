<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $enum_admin_role_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $admins
 * @property-read int|null $admins_count
 * @property-read \App\Models\EnumAdminRole $enumRole
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole whereEnumAdminRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole whereId($value)
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    protected $table = 'admin_roles';

    protected $fillable = ['enum_admin_role_id'];

    /**
     * Relasi ke EnumAdminRole.
     * Satu admin role terhubung ke satu enum admin role.
     */
    public function enumRole()
    {
        return $this->belongsTo(EnumAdminRole::class, 'enum_admin_role_id');
    }

    /**
     * Relasi ke Admins.
     * Satu admin role memiliki banyak admin.
     */
    public function admins()
    {
        return $this->hasMany(Admin::class, 'role_id');
    }
}
