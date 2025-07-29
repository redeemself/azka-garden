<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $enum_stats_type_id
 * @property string|null $period
 * @property array<array-key, mixed>|null $data
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\StatType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereEnumStatsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic wherePeriod($value)
 * @mixin \Eloquent
 */
class Statistic extends Model
{
    protected $table = 'statistics';
    public $timestamps = false;

    protected $fillable = ['type_id', 'period', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(StatType::class, 'type_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
