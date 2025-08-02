<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * InterfaceModel - Fallback Interface Model
 *
 * Created: 2025-08-02 02:50:25 UTC by gerrymulyadi709
 * This is a fallback model to prevent Cart relationship errors
 */
class InterfaceModel extends Model
{
    use HasFactory;

    protected $table = 'interfaces';

    protected $fillable = [
        'name',
        'type',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Get carts using this interface
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'interface_id');
    }

    /**
     * Get products using this interface
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'interface_id');
    }
}
