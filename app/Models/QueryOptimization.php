<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $query_text
 * @property int $execution_time
 * @property string|null $suggested_optimization
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereExecutionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereQueryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereSuggestedOptimization($value)
 * @mixin \Eloquent
 */
class QueryOptimization extends Model
{
    protected $table = 'query_optimizations';
    public $timestamps = false;

    protected $fillable = [
        'query_text', 'execution_time',
        'suggested_optimization', 'status'
    ];

    protected $casts = [
        'execution_time' => 'integer',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
