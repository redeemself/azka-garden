<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReportType;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ReportType> $reportTypes
 * @property-read int|null $report_types_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType whereValue($value)
 * @mixin \Eloquent
 */
class EnumReportType extends Model
{
    protected $table = 'enum_report_type';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function reportTypes()
    {
        return $this->hasMany(ReportType::class, 'enum_report_type_id');
    }
}
