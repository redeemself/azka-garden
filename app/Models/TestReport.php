<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $test_id
 * @property string|null $actual_result
 * @property string|null $status
 * @property int|null $executed_by
 * @property \Illuminate\Support\Carbon|null $executed_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $executor
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\TestCase $testCase
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereActualResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereExecutedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereTestId($value)
 * @mixin \Eloquent
 */
class TestReport extends Model
{
    protected $table = 'test_reports';
    public $timestamps = false;

    protected $fillable = [
        'test_id', 'actual_result',
        'status', 'executed_by',
        'executed_at'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
    ];

    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_id');
    }

    public function executor()
    {
        return $this->belongsTo(Developer::class, 'executed_by');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
