<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\InterfaceModel;
use App\Models\Cart;
use App\Models\ProductLike;
use App\Models\ProductComment;
use App\Models\Order;

/**
 * Product Model - Fixed Relationship Issues
 *
 * Compatible with azka_garden.sql database structure
 * Updated: 2025-08-02 02:29:15 UTC by gerrymulyadi709
 *
 * FIXED ISSUES:
 * ✅ Added product_images() relationship
 * ✅ Fixed all relationship naming conflicts
 * ✅ Added proper database field casting
 * ✅ Compatible with existing cart system
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'stock',
        'price',
        'weight',
        'image_url',
        'status',
        'interface_id',
        'is_featured'
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2', // Match database decimal(12,2)
        'weight' => 'decimal:2', // Match database decimal(8,2)
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $with = []; // Remove auto-loading to prevent issues

    /**
     * Relationship to Category
     * Database: categories table with foreign key category_id
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * FIXED: Primary relationship to ProductImages (from product_images table)
     * This is the main relationship used in the view
     */
    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('is_primary', 'desc');
    }

    /**
     * Legacy support - alias for product_images
     * Maintains backward compatibility
     */
    public function images()
    {
        return $this->product_images();
    }

    /**
     * Get primary image - Optimized
     */
    public function getPrimaryImageAttribute()
    {
        // Try to get cached primary image first
        static $primaryImages = [];

        if (!isset($primaryImages[$this->id])) {
            $primaryImages[$this->id] = $this->product_images()
                ->where('is_primary', 1)
                ->first() ?: $this->product_images()->first();
        }

        return $primaryImages[$this->id];
    }

    /**
     * Get primary image URL with fallback
     */
    public function getPrimaryImageUrlAttribute()
    {
        // First try to get from product_images relationship
        $primaryImage = $this->primary_image;
        if ($primaryImage && !empty($primaryImage->image_url)) {
            return asset($primaryImage->image_url);
        }

        // Fallback to product's direct image_url
        if (!empty($this->image_url)) {
            return asset($this->image_url);
        }

        // Final fallback to placeholder
        return asset('images/produk/placeholder.png');
    }

    /**
     * Get all product images URLs
     */
    public function getImageUrlsAttribute()
    {
        $urls = [];

        // Add primary image first
        if ($this->primary_image_url) {
            $urls[] = $this->primary_image_url;
        }

        // Add other images
        foreach ($this->product_images as $image) {
            $url = asset($image->image_url);
            if (!in_array($url, $urls)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * Relationship to Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * Relationship to Interface
     */
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    /**
     * Relationship to Cart items
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    /**
     * Relationship to ProductLikes (from product_likes table)
     */
    public function likes()
    {
        return $this->hasMany(ProductLike::class, 'product_id');
    }

    /**
     * Relationship to ProductComments (from product_comments table)
     */
    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id');
    }

    /**
     * Get approved comments only
     */
    public function approvedComments()
    {
        return $this->comments()->where('is_approved', 1)->with('user');
    }

    /**
     * Relationship to Orders through order_details pivot table
     */
    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_details', // pivot table name
            'product_id',    // foreign key for product
            'order_id'       // foreign key for order
        )->withPivot(['quantity', 'price', 'subtotal', 'note']);
    }

    /**
     * SCOPES - Query optimization
     */

    /**
     * Scope for active products only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * Scope for products with stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope for products by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * ACCESSOR ATTRIBUTES
     */

    /**
     * Check if product is in stock
     */
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get average rating from approved comments
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedComments()->avg('rating') ?? 0;
    }

    /**
     * Get total likes count
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Get total approved comments count
     */
    public function getCommentsCountAttribute()
    {
        return $this->approvedComments()->count();
    }

    /**
     * UTILITY METHODS
     */

    /**
     * Check if user has liked this product
     */
    public function isLikedByUser($userId)
    {
        if (!$userId) return false;

        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user has commented on this product
     */
    public function hasUserCommented($userId)
    {
        if (!$userId) return false;

        return $this->comments()->where('user_id', $userId)->exists();
    }

    /**
     * Check if product is in user's cart
     */
    public function isInUserCart($userId)
    {
        if (!$userId) return false;

        return $this->carts()->where('user_id', $userId)->exists();
    }

    /**
     * Get product with optimized relationships for listing
     */
    public static function getWithRelationsForListing()
    {
        return static::with([
            'category',
            'product_images' => function ($query) {
                $query->orderBy('is_primary', 'desc');
            }
        ])->active();
    }

    /**
     * Get product with full relationships for detail page
     */
    public static function getWithRelationsForDetail($id)
    {
        return static::with([
            'category',
            'product_images' => function ($query) {
                $query->orderBy('is_primary', 'desc');
            },
            'approvedComments.user',
            'likes'
        ])->findOrFail($id);
    }
}
