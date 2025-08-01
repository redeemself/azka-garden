<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ShippingMethod Model - FINAL DECIMAL ERROR FIX
 * 
 * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
 * - DEFINITIVELY FIXED: Line 73 decimal type error in number_format()
 * - Changed decimal casting to float to prevent type conflicts
 * - Maintained data precision while ensuring PHP compatibility
 * - All number_format operations now work without errors
 */
class ShippingMethod extends Model
{
    protected $fillable = [
        'code',
        'name',
        'service',
        'cost',
        'description',
        'is_active',
        'sort',
        'start_date',
        'end_date',
        'settings'
    ];

    /**
     * CRITICAL FIX: Changed 'cost' from 'decimal:2' to 'float'
     * This prevents the "decimal given" error in number_format()
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    protected $casts = [
        'cost' => 'float',        // FIXED: Changed from 'decimal:2' to 'float'
        'is_active' => 'boolean',
        'sort' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array'
    ];

    /**
     * Scope untuk shipping method yang aktif dan berlaku hari ini
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function scopeActive($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', 1)
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            });
    }

    /**
     * Scope untuk mengurutkan shipping method
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort', 'asc')->orderBy('cost', 'asc');
    }

    /**
     * Accessor untuk format biaya shipping
     * COMPLETELY FIXED: 2025-08-01 12:38:23 UTC by DenuJanuari
     * - No longer needs type conversion since cost is now cast as float
     * - Line 73 error eliminated permanently
     */
    public function getFormattedCostAttribute()
    {
        // NOW WORKS: $this->cost is automatically float, no conversion needed
        return 'Rp' . number_format($this->cost, 0, ',', '.');
    }

    /**
     * Accessor untuk nama tampilan dengan service
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function getDisplayNameAttribute()
    {
        if ($this->service && $this->service !== '-') {
            return $this->name . ' (' . $this->service . ')';
        }
        return $this->name;
    }

    /**
     * Accessor untuk kompatibilitas dengan kode lama (price)
     * FIXED: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function getPriceAttribute()
    {
        return $this->cost; // Now returns float directly
    }

    /**
     * Mutator untuk kompatibilitas dengan kode lama (price)
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['cost'] = $value;
    }

    /**
     * Scope untuk shipping method berdasarkan kode
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Scope untuk shipping method berdasarkan service
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function scopeByService($query, $service)
    {
        return $query->where('service', $service);
    }

    /**
     * Method untuk mendapatkan estimasi waktu berdasarkan settings
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function getEstimatedTimeAttribute()
    {
        $settings = $this->settings ?? [];

        // Default estimasi berdasarkan service
        $estimations = [
            'Internal-Dekat' => '1-2 hari',
            'Internal' => '2-3 hari',
            'Internal-Jauh' => '3-4 hari',
            'EZ' => '2-3 hari',
            'REG' => '2-4 hari',
            'BEST' => '2-3 hari',
            'Sameday' => '1 hari',
            '-' => 'Langsung'
        ];

        return $estimations[$this->service] ?? '2-3 hari';
    }

    /**
     * Method untuk cek apakah shipping method tersedia
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function isAvailable()
    {
        $today = now()->toDateString();

        // Cek status aktif
        if (!$this->is_active) {
            return false;
        }

        // Cek tanggal mulai
        if ($this->start_date && $this->start_date > $today) {
            return false;
        }

        // Cek tanggal berakhir
        if ($this->end_date && $this->end_date < $today) {
            return false;
        }

        return true;
    }

    /**
     * Method untuk mendapatkan icon berdasarkan service
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function getIconAttribute()
    {
        $icons = [
            'Internal-Dekat' => '🚲',
            'Internal' => '🚛',
            'Internal-Jauh' => '🚛',
            'EZ' => '📦',
            'REG' => '📮',
            'BEST' => '⚡',
            'Sameday' => '🏍️',
            '-' => '🏪'
        ];

        return $icons[$this->service] ?? '🚛';
    }

    /**
     * Static method untuk mendapatkan shipping options dalam format array
     * FIXED: 2025-08-01 12:38:23 UTC by DenuJanuari
     * - No type conversion needed since cost is now float
     */
    public static function getOptionsArray()
    {
        return self::active()->ordered()->get()->map(function ($method) {
            return [
                'id' => $method->code,
                'name' => $method->display_name,
                'description' => $method->description,
                'price' => $method->cost, // Direct use, no conversion needed
                'service' => $method->service,
                'icon' => $method->icon,
                'estimated_time' => $method->estimated_time
            ];
        })->toArray();
    }

    /**
     * Static method untuk mencari shipping method berdasarkan kode
     * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public static function findByCode($code)
    {
        return self::active()->byCode($code)->first();
    }

    /**
     * Method untuk mendapatkan detail lengkap shipping method
     * FIXED: 2025-08-01 12:38:23 UTC by DenuJanuari
     * - No type conversion needed since cost is now float
     */
    public function getDetailAttribute()
    {
        return [
            'id' => $this->code,
            'name' => $this->display_name,
            'description' => $this->description,
            'cost' => $this->cost, // Direct use, no conversion needed
            'formatted_cost' => $this->formatted_cost,
            'service' => $this->service,
            'icon' => $this->icon,
            'estimated_time' => $this->estimated_time,
            'is_available' => $this->isAvailable(),
            'settings' => $this->settings
        ];
    }

    /**
     * Helper method untuk safe currency formatting (optional)
     * Added: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public static function formatCurrency($value, $prefix = 'Rp')
    {
        return $prefix . number_format((float) $value, 0, ',', '.');
    }

    /**
     * Alternative formatted cost method for extra safety
     * Added: 2025-08-01 12:38:23 UTC by DenuJanuari
     */
    public function getFormattedPriceAttribute()
    {
        return self::formatCurrency($this->cost);
    }
}