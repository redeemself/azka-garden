<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException whereMessage($value)
 * @mixin \Eloquent
 */
class BusinessException extends Model
{
    protected $table = 'business_exception';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string'; // message serves as identifier
    protected $primaryKey = 'message';

    protected $fillable = [
        'message', 'errorCode'
    ];
}
