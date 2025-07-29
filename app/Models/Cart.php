<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $note
 * @property int $interface_id
 * @property int|null $discount
 * @property int $price
 * @property string|null $promo_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 */
class Cart extends Model
{
    protected $table = 'carts'; // pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'note',
        'interface_id',
        'promo_code',
        'discount',
        'price' // <<< tambahkan ini agar tidak error pada akses property price
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke product (pastikan key 'product_id')
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // relasi ke interface model
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}