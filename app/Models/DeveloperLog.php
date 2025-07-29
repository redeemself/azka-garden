<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $developer_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Developer $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeveloperLog extends Model
{
    protected $table = 'developer_logs';
    public $timestamps = false;

    protected $fillable = [
        'developer_id', 'action',
        'description', 'ip_address'
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
