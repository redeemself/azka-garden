<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cart Model
 * 
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $note
 * @property int $interface_id
 * @property int|null $discount
 * @property int $price
 * @property string|null $promo_code
 * @property array|null $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @property-read float $subtotal
 * 
 * @author mulyadafa
 * @updated 2025-07-30 03:51:39
 */
class Cart extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
        'interface_id',
        'promo_code',
        'discount',
        'price',
        'options'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'json',
        'price' => 'float',
        'discount' => 'integer'
    ];

    /**
     * Get the user that owns the cart item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product that owns the cart item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    /**
     * Get the interface associated with the cart item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interface(): BelongsTo
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
    
    /**
     * Calculate the subtotal for this cart item.
     * 
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        if ($this->price) {
            return $this->price * $this->quantity;
        }
        
        $product = $this->product;
        return $product ? $product->price * $this->quantity : 0;
    }
    
    /**
     * Calculate the final price after discount.
     * 
     * @return float
     */
    public function getFinalPriceAttribute(): float
    {
        $subtotal = $this->subtotal;
        $discount = $this->discount ?? 0;
        
        return max(0, $subtotal - $discount);
    }
}