<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $customer_id
 * @property string $type
 * @property string|null $description
 * @property string|null $status
 * @property string|null $resolution
 * @property int $interface_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DisputeManagement extends Model
{
    protected $table = 'dispute_management';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'customer_id',
        'type', 'description',
        'status', 'resolution'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
