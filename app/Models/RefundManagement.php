<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property numeric|null $amount
 * @property string|null $reason
 * @property string|null $status
 * @property int|null $processed_by
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property int $interface_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereProcessedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RefundManagement extends Model
{
    protected $table = 'refund_management';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'amount',
        'reason', 'status',
        'processed_by', 'processed_at'
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
