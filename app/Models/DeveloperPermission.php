<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $developer_id
 * @property string $module
 * @property bool $can_view
 * @property bool $can_commit
 * @property bool $can_merge
 * @property bool $can_deploy
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Developer $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanDeploy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanMerge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeveloperPermission extends Model
{
    protected $table = 'developer_permissions';
    public $timestamps = false;

    protected $fillable = [
        'developer_id', 'module',
        'can_view', 'can_commit',
        'can_merge', 'can_deploy'
    ];

    protected $casts = [
        'can_view'   => 'boolean',
        'can_commit' => 'boolean',
        'can_merge'  => 'boolean',
        'can_deploy' => 'boolean',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
