<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $title
 * @property array<array-key, mixed>|null $layout
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dashboard extends Model
{
    protected $table = 'dashboards';
    public $timestamps = false;

    protected $fillable = ['title', 'layout'];

    protected $casts = [
        'layout' => 'array',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
