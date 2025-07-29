<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $type
 * @property string|null $notes
 * @property int|null $created_by
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereType($value)
 * @mixin \Eloquent
 */
class StockManagement extends Model
{
    protected $table = 'stock_management';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'quantity',
        'type', 'notes', 'created_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
