<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ProductImage Model - Enhanced for Error Prevention
 *
 * Compatible with product_images table from azka_garden.sql
 * Updated: 2025-08-02 02:29:15 UTC by gerrymulyadi709
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'interface_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'product_id' => 'integer',
        'interface_id' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', 1);
    }

    public function scopeSecondary($query)
    {
        return $query->where('is_primary', 0);
    }

    // Accessors
    public function getFullImageUrlAttribute()
    {
        if (empty($this->image_url)) {
            return asset('images/produk/placeholder.png');
        }

        // If already full URL, return as is
        if (str_starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        return asset($this->image_url);
    }

    // Methods
    public function setPrimary()
    {
        // Remove primary status from other images of the same product
        static::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => 0]);

        // Set this image as primary
        $this->update(['is_primary' => 1]);
    }
}
