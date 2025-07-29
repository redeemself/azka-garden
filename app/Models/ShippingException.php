<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException whereMessage($value)
 * @mixin \Eloquent
 */
class ShippingException extends Model
{
    protected $table = 'shipping_exception';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'message';

    protected $fillable = [
        'message', 'errorCode'
    ];
}
