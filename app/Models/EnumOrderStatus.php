<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus whereValue($value)
 * @mixin \Eloquent
 */
class EnumOrderStatus extends Model
{
    protected $table = 'enum_order_status';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'enum_order_status_id');
    }
}
