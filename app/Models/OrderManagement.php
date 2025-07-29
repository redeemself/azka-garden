<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $admin_id
 * @property string $action
 * @property string|null $notes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderManagement extends Model
{
    protected $table = 'order_management';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'admin_id',
        'action', 'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
