<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Enhanced Cart Model
 * 
 * Updated: 2025-08-01 12:36:10 UTC by DenuJanuari
 * - CRITICAL FIX: Added missing 'name' field for compatibility
 * - Fixed decimal type casting to prevent number_format errors
 * - Enhanced calculation methods with type safety
 * - Added comprehensive validation and helper methods
 * - Improved documentation and error handling
 * 
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $name Added for compatibility
 * @property string|null $note
 * @property int $interface_id
 * @property float|null $discount Fixed casting
 * @property float $price Fixed casting
 * @property string|null $promo_code
 * @property array|null $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @property-read float $subtotal
 * @property-read float $final_price
 * @property-read string $formatted_price
 * @property-read string $formatted_subtotal
 * 
 * @author mulyadafa, enhanced by DenuJanuari
 * @updated 2025-08-01 12:36:10 UTC
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
     * Updated: 2025-08-01 12:36:10 UTC by DenuJanuari
     * - Added 'name' field for compatibility with CartController
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'name',          // CRITICAL: Added for compatibility with existing code
        'note',
        'interface_id',
        'promo_code',
        'discount',
        'price',
        'options'
    ];

    /**
     * The attributes that should be cast.
     * FIXED: 2025-08-01 12:36:10 UTC by DenuJanuari
     * - Changed price and discount to decimal:2 for consistency
     * - Prevents decimal type errors in number_format operations
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',      // FIXED: Consistent with database and other models
        'discount' => 'decimal:2',   // FIXED: Changed from integer to decimal for proper calculations
        'options' => 'json',
        'interface_id' => 'integer'
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
     * Enhanced: 2025-08-01 12:36:10 UTC by DenuJanuari
     * - Added type safety with float conversion
     * - Better fallback logic
     * 
     * @return float
     */
    public function getSubtotalAttribute(): float
    {
        // Use stored price if available, otherwise fallback to product price
        $itemPrice = $this->price ? (float) $this->price : 0;
        
        if ($itemPrice <= 0 && $this->product) {
            $itemPrice = (float) $this->product->price;
        }
        
        $quantity = (int) $this->quantity;
        
        return $itemPrice * $quantity;
    }
    
    /**
     * Calculate the final price after discount.
     * Enhanced: 2025-08-01 12:36:10 UTC by DenuJanuari
     * - Added type safety and validation
     * 
     * @return float
     */
    public function getFinalPriceAttribute(): float
    {
        $subtotal = $this->subtotal;
        $discount = $this->discount ? (float) $this->discount : 0;
        
        return max(0, $subtotal - $discount);
    }

    /**
     * Get formatted price for display.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        $price = (float) $this->price;
        return 'Rp' . number_format($price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal for display.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return string
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get formatted final price for display.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return string
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Get the product name, with fallback to stored name.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return string
     */
    public function getProductNameAttribute(): string
    {
        // Priority: stored name -> product relation name -> fallback
        if ($this->name) {
            return $this->name;
        }
        
        if ($this->product && $this->product->name) {
            return $this->product->name;
        }
        
        return 'Produk Tidak Ditemukan';
    }

    /**
     * Check if the cart item has valid product.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return bool
     */
    public function hasValidProduct(): bool
    {
        return $this->product && $this->product->exists();
    }

    /**
     * Check if the cart item has sufficient stock.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return bool
     */
    public function hasValidStock(): bool
    {
        if (!$this->hasValidProduct()) {
            return false;
        }
        
        return $this->product->stock >= $this->quantity;
    }

    /**
     * Get available stock for this product.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return int
     */
    public function getAvailableStockAttribute(): int
    {
        return $this->hasValidProduct() ? $this->product->stock : 0;
    }

    /**
     * Check if quantity can be incremented.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return bool
     */
    public function canIncrement(): bool
    {
        if (!$this->hasValidProduct()) {
            return false;
        }
        
        return $this->product->stock > $this->quantity;
    }

    /**
     * Check if quantity can be decremented.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return bool
     */
    public function canDecrement(): bool
    {
        return $this->quantity > 1;
    }

    /**
     * Get discount amount for this item.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return float
     */
    public function getDiscountAmountAttribute(): float
    {
        return $this->discount ? (float) $this->discount : 0;
    }

    /**
     * Check if item has discount.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return bool
     */
    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }

    /**
     * Get discount percentage if applicable.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return float
     */
    public function getDiscountPercentageAttribute(): float
    {
        if ($this->subtotal <= 0 || !$this->hasDiscount()) {
            return 0;
        }
        
        return ($this->discount_amount / $this->subtotal) * 100;
    }

    /**
     * Get cart item summary for display.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @return array
     */
    public function getSummaryAttribute(): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'price' => (float) $this->price,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount_amount,
            'final_price' => $this->final_price,
            'formatted_price' => $this->formatted_price,
            'formatted_subtotal' => $this->formatted_subtotal,
            'formatted_final_price' => $this->formatted_final_price,
            'has_discount' => $this->hasDiscount(),
            'discount_percentage' => $this->discount_percentage,
            'has_valid_product' => $this->hasValidProduct(),
            'has_valid_stock' => $this->hasValidStock(),
            'available_stock' => $this->available_stock,
            'can_increment' => $this->canIncrement(),
            'can_decrement' => $this->canDecrement()
        ];
    }

    /**
     * Scope to get cart items for a specific user.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get cart items with valid products.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithValidProducts($query)
    {
        return $query->whereHas('product');
    }

    /**
     * Scope to get cart items with sufficient stock.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithValidStock($query)
    {
        return $query->whereHas('product', function($q) {
            $q->whereRaw('products.stock >= carts.quantity');
        });
    }

    /**
     * Calculate total for multiple cart items.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @param \Illuminate\Database\Eloquent\Collection $cartItems
     * @return array
     */
    public static function calculateTotal($cartItems): array
    {
        $subtotal = 0;
        $totalDiscount = 0;
        $finalTotal = 0;
        $totalQuantity = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->subtotal;
            $totalDiscount += $item->discount_amount;
            $finalTotal += $item->final_price;
            $totalQuantity += $item->quantity;
        }

        return [
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'final_total' => $finalTotal,
            'total_quantity' => $totalQuantity,
            'item_count' => $cartItems->count(),
            'formatted_subtotal' => 'Rp' . number_format($subtotal, 0, ',', '.'),
            'formatted_total_discount' => 'Rp' . number_format($totalDiscount, 0, ',', '.'),
            'formatted_final_total' => 'Rp' . number_format($finalTotal, 0, ',', '.')
        ];
    }

    /**
     * Static method to get user's cart summary.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     * 
     * @param int $userId
     * @return array
     */
    public static function getUserCartSummary($userId): array
    {
        $cartItems = self::with('product')->forUser($userId)->get();
        return self::calculateTotal($cartItems);
    }

    /**
     * Boot method for model events.
     * Added: 2025-08-01 12:36:10 UTC by DenuJanuari
     */
    protected static function boot()
    {
        parent::boot();

        // Set default interface_id if not provided
        static::creating(function ($cart) {
            if (!$cart->interface_id) {
                $cart->interface_id = 1; // Default to user interface
            }
        });

        // Update product name from product if not set
        static::creating(function ($cart) {
            if (!$cart->name && $cart->product) {
                $cart->name = $cart->product->name;
            }
        });

        // Validate stock before saving
        static::saving(function ($cart) {
            if ($cart->product && $cart->product->stock < $cart->quantity) {
                throw new \Exception('Insufficient stock for product: ' . $cart->product->name);
            }
        });
    }
}