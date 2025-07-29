<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\EnumOrderStatus;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Payment;

/**
 * @property int $id
 * @property int $user_id
 * @property string $order_code
 * @property string $order_date
 * @property int $enum_order_status_id
 * @property string $total_price
 * @property string $shipping_cost
 * @property string|null $note
 * @property string|null $payment_method
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\EnumOrderStatus $status
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Shipping $shipping
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read \App\Models\Payment $payment
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereEnumOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentMethod($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id', 'order_code', 'order_date', 'enum_order_status_id',
        'total_price', 'shipping_cost', 'note', 'payment_method', 'interface_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'order_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke order_details
     * Agar kompatibel dengan eager loading 'details'
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Relasi ke orderDetails (nama lama/alternatif, bisa dipakai di tempat lain)
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Relasi status ke tabel enum_order_status
     * Agar $order->status->value bisa di-blade
     */
    public function status()
    {
        return $this->belongsTo(EnumOrderStatus::class, 'enum_order_status_id');
    }

    /**
     * Relasi ke products melalui pivot order_details (many-to-many)
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_details',
            'order_id',
            'product_id'
        );
    }

    /**
     * Relasi ke shipping (satu order satu shipping)
     */
    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id', 'id');
    }

    /**
     * Relasi ke payment (satu order satu payment, relasi utama)
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    /**
     * Relasi ke payments (satu order bisa punya banyak payment jika multi-attempt)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }
}
