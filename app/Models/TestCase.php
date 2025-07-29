<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $test_type
 * @property string|null $expected_result
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestReport> $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereExpectedResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereTestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereTitle($value)
 * @mixin \Eloquent
 */
class TestCase extends Model
{
    protected $table = 'test_cases';
    public $timestamps = false;

    protected $fillable = [
        'title', 'description',
        'test_type', 'expected_result',
        'status'
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    public function reports()
    {
        return $this->hasMany(TestReport::class, 'test_id');
    }
}
