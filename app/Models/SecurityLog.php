<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $related_to
 * @property string|null $event
 * @property string|null $description
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereRelatedTo($value)
 * @mixin \Eloquent
 */
class SecurityLog extends Model
{
    protected $table = 'security_logs';
    public $timestamps = false;

    protected $fillable = [
        'related_to', 'event', 'description'
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
