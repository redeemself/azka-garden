<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $component
 * @property string $status
 * @property numeric|null $cpu_usage
 * @property numeric|null $memory_usage
 * @property numeric|null $disk_usage
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereCpuUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereDiskUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereMemoryUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereStatus($value)
 * @mixin \Eloquent
 */
class SystemHealth extends Model
{
    protected $table = 'system_health';
    public $timestamps = false;

    protected $fillable = [
        'component', 'status', 'cpu_usage', 'memory_usage', 'disk_usage'
    ];

    protected $casts = [
        'cpu_usage'    => 'decimal:2',
        'memory_usage' => 'decimal:2',
        'disk_usage'   => 'decimal:2',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
