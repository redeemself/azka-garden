<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistic> $statistics
 * @property-read int|null $statistics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType whereValue($value)
 * @mixin \Eloquent
 */
class EnumStatsType extends Model
{
    protected $table = 'enum_stats_type';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'type_id');
    }
}
