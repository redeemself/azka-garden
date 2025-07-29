<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $supplier_id
 * @property string|null $status
 * @property numeric|null $total_amount
 * @property string|null $payment_status
 * @property \Illuminate\Support\Carbon|null $delivery_date
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\SupplierManagement $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereTotalAmount($value)
 * @mixin \Eloquent
 */
class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    public $timestamps = false;

    protected $fillable = [
        'supplier_id', 'status',
        'total_amount', 'payment_status',
        'delivery_date'
    ];

    protected $casts = [
        'total_amount'   => 'decimal:2',
        'delivery_date'  => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(SupplierManagement::class, 'supplier_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
