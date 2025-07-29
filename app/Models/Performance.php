<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $metric_name
 * @property numeric $value
 * @property string|null $unit
 * @property \Illuminate\Support\Carbon $timestamp
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereMetricName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereValue($value)
 * @mixin \Eloquent
 */
class Performance extends Model
{
    protected $table = 'performances';
    public $timestamps = false;

    protected $fillable = [
        'metric_name', 'value',
        'unit', 'timestamp'
    ];

    protected $casts = [
        'value'     => 'decimal:2',
        'timestamp' => 'datetime',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
