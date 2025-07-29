<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EnumStatsType;

/**
 * 
 *
 * @property int $stats_type_id
 * @property string $code
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property-read EnumStatsType|null $enumType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistic> $statistics
 * @property-read int|null $statistics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereStatsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StatType extends Model
{
    protected $table = 'stats_types';
    public $timestamps = false;

    protected $fillable = ['enum_stats_type_id'];

    public function enumType()
    {
        return $this->belongsTo(EnumStatsType::class, 'enum_stats_type_id');
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'type_id');
    }
}
