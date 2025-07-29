<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApiEndpoint;

/**
 * 
 *
 * @property int $id
 * @property int $endpoint_id
 * @property \Illuminate\Support\Carbon $timestamp
 * @property int $response_time
 * @property int $status_code
 * @property numeric|null $error_rate
 * @property int $interface_id
 * @property-read ApiEndpoint $endpoint
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereEndpointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereErrorRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereTimestamp($value)
 * @mixin \Eloquent
 */
class ApiMetric extends Model
{
    protected $table = 'api_metrics';
    public $timestamps = false;

    protected $fillable = [
        'endpoint_id', 'timestamp',
        'response_time', 'status_code',
        'error_rate'
    ];

    protected $casts = [
        'timestamp'     => 'datetime',
        'response_time' => 'integer',
        'status_code'   => 'integer',
        'error_rate'    => 'decimal:2',
    ];

    public function endpoint()
    {
        return $this->belongsTo(ApiEndpoint::class, 'endpoint_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
