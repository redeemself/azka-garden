<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus whereValue($value)
 * @mixin \Eloquent
 */
class EnumPaymentStatus extends Model
{
    protected $table = 'enum_payment_status';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'enum_payment_status_id');
    }
}
