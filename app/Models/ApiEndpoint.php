<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InterfaceModel;
use App\Models\ApiDocumentation;
use App\Models\ApiMetric;

/**
 * 
 *
 * @property int $id
 * @property string $path
 * @property string $method
 * @property string|null $version
 * @property string|null $description
 * @property bool $auth_required
 * @property int|null $rate_limit
 * @property string $created_at
 * @property int $interface_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ApiDocumentation> $documentations
 * @property-read int|null $documentations_count
 * @property-read InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ApiMetric> $metrics
 * @property-read int|null $metrics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereAuthRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereRateLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereVersion($value)
 * @mixin \Eloquent
 */
class ApiEndpoint extends Model
{
    protected $table = 'api_endpoints';
    public $timestamps = false;

    protected $fillable = [
        'path',
        'method',
        'version',
        'description',
        'auth_required',
        'rate_limit',
        'interface_id',
    ];

    protected $casts = [
        'auth_required' => 'boolean',
        'rate_limit'    => 'integer',
        'version'       => 'string',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    public function documentations()
    {
        return $this->hasMany(ApiDocumentation::class, 'endpoint_id');
    }

    public function metrics()
    {
        return $this->hasMany(ApiMetric::class, 'endpoint_id');
    }
}
