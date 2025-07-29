<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException whereMessage($value)
 * @mixin \Eloquent
 */
class PaymentException extends Model
{
    protected $table = 'payment_exception';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'message';

    protected $fillable = [
        'message', 'errorCode'
    ];
}
