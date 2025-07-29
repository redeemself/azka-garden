<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\InterfaceModel;

/**
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property string|null $image_url
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'product_id', 'user_id', 'rating', 'comment', 'image_url', 'interface_id'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        // Pastikan import Product di atas, dan gunakan foreign key secara eksplisit
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        // Pastikan import User di atas, dan gunakan foreign key secara eksplisit
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model InterfaceModel
     */
    public function interface()
    {
        // Pastikan import InterfaceModel di atas, dan gunakan foreign key secara eksplisit
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
