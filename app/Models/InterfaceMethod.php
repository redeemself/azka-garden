<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $interface_id
 * @property string $method_name
 * @property string $return_type
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereReturnType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InterfaceMethod extends Model
{
    protected $table = 'interface_methods';
    public $timestamps = false;

    protected $fillable = [
        'interface_id', 'method_name',
        'return_type', 'description'
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
