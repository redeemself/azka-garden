<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\InterfaceModel;
use App\Models\Cart;
use App\Models\ProductLike;
use App\Models\Order;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'category_id', 'name', 'description', 'stock', 'price', 'weight', 'image_url', 'status', 'interface_id', 'is_featured'
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'float',
        'weight'=> 'float',
        'status'=> 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Perbaikan: explicit foreign key 'product_id' agar relasi benar
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    // Tambahkan relasi likes untuk mendukung withCount('likes')
    public function likes()
    {
        return $this->hasMany(ProductLike::class, 'product_id');
    }

    /**
     * Relasi ke orders melalui pivot order_details (many-to-many)
     */
    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_details', // nama tabel pivot
            'product_id',    // foreign key di pivot untuk produk
            'order_id'       // foreign key di pivot untuk order
        );
    }
}