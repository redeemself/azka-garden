<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $version
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $notes
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereVersion($value)
 * @mixin \Eloquent
 */
class Deployment extends Model
{
    protected $table = 'deployments';
    public $timestamps = false;

    protected $fillable = [
        'version', 'date',
        'notes', 'status'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
