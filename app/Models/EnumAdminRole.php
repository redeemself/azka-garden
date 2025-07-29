<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminRole> $adminRoles
 * @property-read int|null $admin_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole whereValue($value)
 * @mixin \Eloquent
 */
class EnumAdminRole extends Model
{
    protected $table = 'enum_admin_role';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function adminRoles()
    {
        return $this->hasMany(AdminRole::class, 'enum_admin_role_id');
    }
}
