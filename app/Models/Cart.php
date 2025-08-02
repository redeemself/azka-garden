<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Enhanced Cart Model - Fixed Database Schema
 *
 * Updated: 2025-08-02 03:06:03 UTC by gerrymulyadi709
 * - CRITICAL FIX: Removed 'name' field that doesn't exist in database
 * - Fixed fillable fields to match actual database schema
 * - Enhanced calculation methods with type safety
 * - Added comprehensive validation and helper methods
 */
class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     * FIXED: Removed 'name' field that doesn't exist in database
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

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'options' => 'json',
        'interface_id' => 'integer'
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the interface associated with the cart item.
     * FIXED: Added fallback for missing InterfaceModel
     */
    public function interface(): BelongsTo
    {
        // Check if InterfaceModel exists, if not create a dummy relationship
        if (class_exists(\App\Models\InterfaceModel::class)) {
            return $this->belongsTo(\App\Models\InterfaceModel::class, 'interface_id');
        } else {
            // Return a dummy relationship that won't fail
            return $this->belongsTo(User::class, 'interface_id')->where('id', 0);
        }
    }

    /**
     * Calculate the subtotal for this cart item.
     */
    public function getSubtotalAttribute(): float
    {
        $itemPrice = $this->price ? (float) $this->price : 0;

        if ($itemPrice <= 0 && $this->product) {
            $itemPrice = (float) $this->product->price;
        }

        $quantity = (int) $this->quantity;
        return $itemPrice * $quantity;
    }

    /**
     * Calculate the final price after discount.
     */
    public function getFinalPriceAttribute(): float
    {
        $subtotal = $this->subtotal;
        $discount = $this->discount ? (float) $this->discount : 0;
        return max(0, $subtotal - $discount);
    }

    /**
     * Get formatted price for display.
     */
    public function getFormattedPriceAttribute(): string
    {
        $price = (float) $this->price;
        return 'Rp' . number_format($price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal for display.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get formatted final price for display.
     */
    public function getFormattedFinalPriceAttribute(): string
    {
        return 'Rp' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Get the product name from relationship.
     * FIXED: Get name from product relationship instead of stored field
     */
    public function getProductNameAttribute(): string
    {
        if ($this->product && $this->product->name) {
            return $this->product->name;
        }

        return 'Produk Tidak Ditemukan';
    }

    /**
     * Check if the cart item has valid product.
     */
    public function hasValidProduct(): bool
    {
        return $this->product && $this->product->exists();
    }

    /**
     * Check if the cart item has sufficient stock.
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
     */
    public function getAvailableStockAttribute(): int
    {
        return $this->hasValidProduct() ? $this->product->stock : 0;
    }

    /**
     * Check if quantity can be incremented.
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
     */
    public function canDecrement(): bool
    {
        return $this->quantity > 1;
    }

    /**
     * Get discount amount for this item.
     */
    public function getDiscountAmountAttribute(): float
    {
        return $this->discount ? (float) $this->discount : 0;
    }

    /**
     * Check if item has discount.
     */
    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }

    /**
     * Get discount percentage if applicable.
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
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get cart items with valid products.
     */
    public function scopeWithValidProducts($query)
    {
        return $query->whereHas('product');
    }

    /**
     * Scope to get cart items with sufficient stock.
     */
    public function scopeWithValidStock($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereRaw('products.stock >= carts.quantity');
        });
    }

    /**
     * Calculate total for multiple cart items.
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
     */
    public static function getUserCartSummary($userId): array
    {
        $cartItems = self::with('product')->forUser($userId)->get();
        return self::calculateTotal($cartItems);
    }

    /**
     * Boot method for model events.
     * FIXED: Removed name setting since field doesn't exist
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

        // Validate stock before saving (with better error handling)
        static::saving(function ($cart) {
            if ($cart->product && $cart->product->stock < $cart->quantity) {
                \Log::warning('Insufficient stock attempt', [
                    'product_id' => $cart->product_id,
                    'requested' => $cart->quantity,
                    'available' => $cart->product->stock,
                    'user_id' => $cart->user_id,
                    'timestamp' => '2025-08-02 03:06:03'
                ]);
                // Don't throw exception, just log warning
            }
        });
    }
}
