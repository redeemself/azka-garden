<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_method',
        'payment_method',
        'total',
        'status',
        'order_code',
        'order_date',
        'enum_order_status_id',
        'total_price',
        'shipping_cost',
    ];

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias relationship for 'details'
     */
    public function details()
    {
        return $this->hasMany(OrderItem::class);
    }

    // JANGAN tambahkan relasi shipping() jika tidak ada Shipping model/relasi
}
