<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $admin_id
 * @property string $module
 * @property bool $can_view
 * @property bool $can_create
 * @property bool $can_edit
 * @property bool $can_delete
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminPermission extends Model
{
    protected $table = 'admin_permissions';
    protected $fillable = [
        'admin_id', 'module',
        'can_view', 'can_create',
        'can_edit', 'can_delete'
    ];

    protected $casts = [
        'can_view'   => 'boolean',
        'can_create' => 'boolean',
        'can_edit'   => 'boolean',
        'can_delete' => 'boolean',
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
