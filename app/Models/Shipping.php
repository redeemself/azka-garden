<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $courier
 * @property string $service
 * @property string|null $tracking_number
 * @property numeric $shipping_cost
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $estimated_delivery
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCourier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereEstimatedDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shipping extends Model
{
    protected $table = 'shippings';
    protected $fillable = [
        'order_id', 'courier', 'service',
        'tracking_number', 'shipping_cost',
        'status', 'estimated_delivery'
    ];

    protected $casts = [
        'shipping_cost'      => 'decimal:2',
        'estimated_delivery' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
