<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string|null $promo_code
 * @property string|null $title
 * @property string|null $description
 * @property string|null $discount_type
 * @property float|null $discount_value
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 */
class Promotion extends Model
{
    protected $table = 'promotions';
    public $timestamps = false;

    protected $fillable = [
        'promo_code', 'title', 'description',
        'discount_type', 'discount_value',
        'start_date', 'end_date', 'status', 'interface_id'
    ];

    protected $casts = [
        'start_date'     => 'datetime',
        'end_date'       => 'datetime',
        'discount_value' => 'float',
        'status'         => 'boolean',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    /**
     * Scope a query to only include active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $now = now();
                $q->where(function ($sub) use ($now) {
                    $sub->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })->where(function ($sub) use ($now) {
                    $sub->whereNull('end_date')->orWhere('end_date', '>=', $now);
                });
            });
    }

    /**
     * Check if the promotion is valid at a given date/time.
     */
    public function isValid($date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        if (!$this->status) return false;
        if ($this->start_date && $date->lt($this->start_date)) return false;
        if ($this->end_date && $date->gt($this->end_date)) return false;
        return true;
    }

    /**
     * Get the discount amount for a given price.
     */
    public function getDiscountAmount($price)
    {
        if ($this->discount_type === 'fixed') {
            return floatval($this->discount_value);
        }
        if ($this->discount_type === 'percent') {
            return round($price * floatval($this->discount_value) / 100, 2);
        }
        return 0;
    }
}
