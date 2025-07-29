<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $deployment_id
 * @property string|null $content
 * @property int|null $created_by
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $creator
 * @property-read \App\Models\Deployment $deployment
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereDeploymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereInterfaceId($value)
 * @mixin \Eloquent
 */
class ReleaseNote extends Model
{
    protected $table = 'release_notes';
    public $timestamps = false;

    protected $fillable = [
        'deployment_id', 'content',
        'created_by'
    ];

    public function deployment()
    {
        return $this->belongsTo(Deployment::class);
    }

    public function creator()
    {
        return $this->belongsTo(Developer::class, 'created_by');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
