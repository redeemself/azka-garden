<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\EnumOrderStatus;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Payment;

/**
 * Order Model for Azka Garden
 * Updated: 2025-07-31 15:53:31 by DenuJanuari
 * Enhanced to support both new checkout system and existing database structure
 *
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
 * 
 * Additional properties for enhanced checkout system:
 * @property string|null $order_number (alias for order_code)
 * @property string|null $status (computed from enum_order_status)
 * @property float|null $subtotal (computed from order_details)
 * @property float|null $discount_amount
 * @property float|null $tax_amount
 * @property float|null $shipping_fee (alias for shipping_cost)
 * @property float|null $total_amount (alias for total_price)
 * @property string|null $payment_status
 * @property string|null $shipping_method
 * @property array|null $shipping_address
 * @property string|null $promo_code
 * 
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
    use HasFactory;

    protected $table = 'orders';

    /**
     * Enhanced fillable fields to support both old and new structure
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    protected $fillable = [
        // Existing database fields
        'user_id',
        'order_code',
        'order_date',
        'enum_order_status_id',
        'total_price',
        'shipping_cost',
        'note',
        'payment_method',
        'interface_id',

        // Additional fields for enhanced checkout (if columns exist)
        'order_number',     // alias for order_code
        'status',           // if direct status field exists
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_fee',     // alias for shipping_cost
        'total_amount',     // alias for total_price
        'payment_status',
        'shipping_method',
        'shipping_address',
        'promo_code'
    ];

    /**
     * Enhanced casts for proper data handling
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'order_date' => 'datetime',

        // Enhanced casts for checkout system
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Accessor for order_number (alias for order_code)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getOrderNumberAttribute()
    {
        return $this->order_code;
    }

    /**
     * Mutator for order_number (sets order_code)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function setOrderNumberAttribute($value)
    {
        $this->attributes['order_code'] = $value;
    }

    /**
     * Accessor for shipping_fee (alias for shipping_cost)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getShippingFeeAttribute()
    {
        return $this->shipping_cost;
    }

    /**
     * Mutator for shipping_fee (sets shipping_cost)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function setShippingFeeAttribute($value)
    {
        $this->attributes['shipping_cost'] = $value;
    }

    /**
     * Accessor for total_amount (alias for total_price)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }

    /**
     * Mutator for total_amount (sets total_price)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_price'] = $value;
    }

    /**
     * Accessor for subtotal (computed from order_details if not stored)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getSubtotalAttribute()
    {
        // If subtotal is stored in database, return it
        if (isset($this->attributes['subtotal'])) {
            return $this->attributes['subtotal'];
        }

        // Otherwise, compute from order details
        return $this->orderDetails->sum('subtotal');
    }

    /**
     * Accessor for computed status string from enum
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getStatusStringAttribute()
    {
        return $this->status ? $this->status->value : 'unknown';
    }

    /**
     * Relasi ke User
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke order_details (alias: details)
     * Agar kompatibel dengan eager loading 'details'
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Relasi ke orderDetails (nama standar)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Alias for orderDetails to support OrderItem naming convention
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    /**
     * Relasi status ke tabel enum_order_status
     * Agar $order->status->value bisa di-blade
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function status()
    {
        return $this->belongsTo(EnumOrderStatus::class, 'enum_order_status_id');
    }

    /**
     * Relasi ke products melalui pivot order_details (many-to-many)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_details',
            'order_id',
            'product_id'
        )->withPivot(['quantity', 'price', 'subtotal', 'note']);
    }

    /**
     * Relasi ke shipping (satu order satu shipping)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id', 'id');
    }

    /**
     * Relasi ke payment (satu order satu payment, relasi utama)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    /**
     * Relasi ke payments (satu order bisa punya banyak payment jika multi-attempt)
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    /**
     * Scope untuk filter berdasarkan status
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function scopeWithStatus($query, $statusValue)
    {
        return $query->whereHas('status', function ($q) use ($statusValue) {
            $q->where('value', $statusValue);
        });
    }

    /**
     * Scope untuk filter berdasarkan user
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function scopeByDate($query, $startDate, $endDate = null)
    {
        $query->whereDate('order_date', '>=', $startDate);

        if ($endDate) {
            $query->whereDate('order_date', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Helper method untuk mendapatkan total item quantity
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getTotalItemsAttribute()
    {
        return $this->orderDetails->sum('quantity');
    }

    /**
     * Helper method untuk format order number yang user-friendly
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getFormattedOrderNumberAttribute()
    {
        return $this->order_code ?: $this->order_number ?: "ORD-{$this->id}";
    }

    /**
     * Helper method untuk mendapatkan status pembayaran
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function getPaymentStatusAttribute()
    {
        if (isset($this->attributes['payment_status'])) {
            return $this->attributes['payment_status'];
        }

        // Fallback: check dari relasi payment
        if ($this->payment) {
            return $this->payment->status->value ?? 'pending';
        }

        return 'pending';
    }

    /**
     * Helper method untuk check apakah order bisa dibatalkan
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function canBeCancelled()
    {
        $allowedStatuses = ['pending', 'confirmed', 'processing'];
        return in_array($this->status_string, $allowedStatuses);
    }

    /**
     * Helper method untuk check apakah order sudah selesai
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    public function isCompleted()
    {
        return $this->status_string === 'completed';
    }

    /**
     * Boot method untuk auto-generate order code jika tidak ada
     * Updated: 2025-07-31 15:53:31 by DenuJanuari
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Auto-generate order_code jika belum ada
            if (empty($order->order_code) && empty($order->order_number)) {
                $order->order_code = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            }

            // Set default interface_id jika belum ada
            if (empty($order->interface_id)) {
                $order->interface_id = 8; // Default interface ID
            }

            // Set default enum_order_status_id jika belum ada (1 = pending)
            if (empty($order->enum_order_status_id)) {
                $order->enum_order_status_id = 1;
            }

            // Set order_date jika belum ada
            if (empty($order->order_date)) {
                $order->order_date = now();
            }
        });
    }
}
