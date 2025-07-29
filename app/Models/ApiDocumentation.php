<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApiEndpoint;
use App\Models\Developer;

/**
 * 
 *
 * @property int $id
 * @property int $endpoint_id
 * @property string|null $version
 * @property string|null $content
 * @property array<array-key, mixed>|null $examples
 * @property int|null $updated_by
 * @property string $created_at
 * @property int $interface_id
 * @property-read ApiEndpoint $endpoint
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read Developer|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereEndpointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereExamples($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereVersion($value)
 * @mixin \Eloquent
 */
class ApiDocumentation extends Model
{
    protected $table = 'api_documentations';
    public $timestamps = false;

    protected $fillable = [
        'endpoint_id', 'version',
        'content', 'examples',
        'updated_by'
    ];

    protected $casts = [
        'examples' => 'array',
    ];

    public function endpoint()
    {
        return $this->belongsTo(ApiEndpoint::class, 'endpoint_id');
    }

    public function updater()
    {
        return $this->belongsTo(Developer::class, 'updated_by');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
