<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Promotion Model
 * 
 * @property int $id
 * @property string|null $promo_code
 * @property string|null $title
 * @property string|null $description
 * @property string|null $discount_type
 * @property float|null $discount_value
 * @property float|null $minimum_purchase
 * @property float|null $maximum_discount
 * @property int|null $usage_limit
 * @property int $used_count
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $interface_id
 * @property-read \App\Models\InterfaceModel|null $interface
 * @property-read string $discount_display
 * @property-read int|null $remaining_usage
 * 
 * Updated: 2025-07-31 17:13:30 by DenuJanuari
 */
class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    // Enable timestamps for better tracking
    public $timestamps = true;

    protected $fillable = [
        'promo_code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'minimum_purchase',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
        'interface_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'discount_value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'interface_id' => 'integer'
    ];

    protected $attributes = [
        'used_count' => 0,
        'status' => true
    ];

    /**
     * Relationship with InterfaceModel
     */
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    /**
     * Scope for active promotions
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for valid date range
     */
    public function scopeValidDate($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
        });
    }

    /**
     * Scope for available promotions (not exceeded usage limit)
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('usage_limit')
                ->orWhereRaw('used_count < usage_limit');
        });
    }

    /**
     * Scope for currently valid promotions (active + valid date + available)
     */
    public function scopeValid($query)
    {
        return $query->active()->validDate()->available();
    }

    /**
     * Scope to filter by promo code
     */
    public function scopeByCode($query, $promoCode)
    {
        return $query->where('promo_code', strtoupper(trim($promoCode)));
    }

    /**
     * Check if promotion is currently valid
     */
    public function isValid($date = null): bool
    {
        $checkDate = $date ? Carbon::parse($date) : now();

        // Check status
        if (!$this->status) {
            return false;
        }

        // Check date range
        if ($this->start_date && $checkDate->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $checkDate->gt($this->end_date)) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if promotion is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date && now()->gt($this->end_date);
    }

    /**
     * Check if promotion has not started yet
     */
    public function isNotStarted(): bool
    {
        return $this->start_date && now()->lt($this->start_date);
    }

    /**
     * Check if promotion has reached usage limit
     */
    public function isUsageLimitReached(): bool
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    /**
     * Calculate discount amount for a given price
     */
    public function calculateDiscount($amount): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        // Check minimum purchase requirement
        if ($this->minimum_purchase && $amount < $this->minimum_purchase) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percent') {
            $discount = $amount * ($this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed') {
            $discount = $this->discount_value;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        // Ensure discount doesn't exceed the total amount
        return min($discount, $amount);
    }

    /**
     * Get the discount amount for a given price (legacy method for compatibility)
     */
    public function getDiscountAmount($price): float
    {
        return $this->calculateDiscount($price);
    }

    /**
     * Increment usage count safely
     */
    public function incrementUsage(): bool
    {
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        $this->increment('used_count');
        return true;
    }

    /**
     * Decrement usage count safely (for refunds/cancellations)
     */
    public function decrementUsage(): bool
    {
        if ($this->used_count <= 0) {
            return false;
        }

        $this->decrement('used_count');
        return true;
    }

    /**
     * Get formatted discount display
     */
    public function getDiscountDisplayAttribute(): string
    {
        if ($this->discount_type === 'percent') {
            return number_format($this->discount_value, 0) . '%';
        }
        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }

    /**
     * Get remaining usage count
     */
    public function getRemainingUsageAttribute(): ?int
    {
        if (!$this->usage_limit) {
            return null; // Unlimited
        }
        return max(0, $this->usage_limit - $this->used_count);
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentageAttribute(): float
    {
        if (!$this->usage_limit) {
            return 0;
        }
        return min(100, ($this->used_count / $this->usage_limit) * 100);
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        if (!$this->status) {
            return 'Inactive';
        }

        if ($this->isNotStarted()) {
            return 'Not Started';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($this->isUsageLimitReached()) {
            return 'Usage Limit Reached';
        }

        return 'Active';
    }

    /**
     * Get formatted start date
     */
    public function getFormattedStartDateAttribute(): ?string
    {
        return $this->start_date ? $this->start_date->format('d-m-Y H:i') : null;
    }

    /**
     * Get formatted end date
     */
    public function getFormattedEndDateAttribute(): ?string
    {
        return $this->end_date ? $this->end_date->format('d-m-Y H:i') : null;
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        $days = now()->diffInDays($this->end_date, false);
        return $days >= 0 ? $days : 0;
    }

    /**
     * Check if promotion will expire soon (within 7 days)
     */
    public function isExpiringSoon(): bool
    {
        $daysUntilExpiry = $this->days_until_expiry;
        return $daysUntilExpiry !== null && $daysUntilExpiry <= 7 && $daysUntilExpiry > 0;
    }

    /**
     * Get validation errors for the promotion
     */
    public function getValidationErrors(): array
    {
        $errors = [];

        if (!$this->status) {
            $errors[] = 'Promotion is inactive';
        }

        if ($this->isNotStarted()) {
            $errors[] = 'Promotion has not started yet';
        }

        if ($this->isExpired()) {
            $errors[] = 'Promotion has expired';
        }

        if ($this->isUsageLimitReached()) {
            $errors[] = 'Usage limit has been reached';
        }

        return $errors;
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promotion) {
            // Ensure promo_code is uppercase
            if ($promotion->promo_code) {
                $promotion->promo_code = strtoupper(trim($promotion->promo_code));
            }

            // Set default used_count if not provided
            if (is_null($promotion->used_count)) {
                $promotion->used_count = 0;
            }
        });

        static::updating(function ($promotion) {
            // Ensure promo_code is uppercase
            if ($promotion->promo_code) {
                $promotion->promo_code = strtoupper(trim($promotion->promo_code));
            }
        });

        static::created(function ($promotion) {
            \Log::info('Promotion created', [
                'id' => $promotion->id,
                'promo_code' => $promotion->promo_code,
                'discount_type' => $promotion->discount_type,
                'discount_value' => $promotion->discount_value,
                'timestamp' => '2025-07-31 17:13:30',
                'user' => 'DenuJanuari'
            ]);
        });

        static::updated(function ($promotion) {
            \Log::info('Promotion updated', [
                'id' => $promotion->id,
                'promo_code' => $promotion->promo_code,
                'changes' => $promotion->getChanges(),
                'timestamp' => '2025-07-31 17:13:30',
                'user' => 'DenuJanuari'
            ]);
        });
    }

    /**
     * Convert the model instance to an array for API responses
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'promo_code' => $this->promo_code,
            'title' => $this->title,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'discount_display' => $this->discount_display,
            'minimum_purchase' => $this->minimum_purchase,
            'maximum_discount' => $this->maximum_discount,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'remaining_usage' => $this->remaining_usage,
            'usage_percentage' => $this->usage_percentage,
            'start_date' => $this->start_date?->toISOString(),
            'end_date' => $this->end_date?->toISOString(),
            'formatted_start_date' => $this->formatted_start_date,
            'formatted_end_date' => $this->formatted_end_date,
            'days_until_expiry' => $this->days_until_expiry,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'is_valid' => $this->isValid(),
            'is_expired' => $this->isExpired(),
            'is_expiring_soon' => $this->isExpiringSoon(),
            'validation_errors' => $this->getValidationErrors(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString()
        ];
    }
}
