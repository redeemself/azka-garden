<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductComment Model - Database Schema Aligned
 *
 * Compatible with product_comments table from azka_garden.sql
 * Updated: 2025-08-02 02:16:06 UTC by gerrymulyadi709
 */
class ProductComment extends Model
{
    use HasFactory;

    protected $table = 'product_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'rating',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product that was commented on.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Scope for approved comments only
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 1);
    }

    /**
     * Scope for pending comments
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', 0);
    }

    /**
     * Get formatted rating (1-5 stars)
     */
    public function getFormattedRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get short content for display
     */
    public function getShortContentAttribute()
    {
        return \Str::limit($this->content, 100);
    }

    /**
     * Check if comment is approved
     */
    public function getIsApprovedAttribute()
    {
        return (bool) $this->attributes['is_approved'];
    }
}
