<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EnumReportType;

/**
 * 
 *
 * @property int $id
 * @property int $enum_report_type_id
 * @property-read EnumReportType $enumType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereEnumReportTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereId($value)
 * @mixin \Eloquent
 */
class ReportType extends Model
{
    protected $table = 'report_types';
    public $timestamps = false;

    protected $fillable = [
        'enum_report_type_id', 'title', 'parameters', 'data', 'format'
    ];

    protected $casts = [
        'parameters' => 'array',
        'data'       => 'array',
    ];

    public function enumType()
    {
        return $this->belongsTo(EnumReportType::class, 'enum_report_type_id');
    }
}
