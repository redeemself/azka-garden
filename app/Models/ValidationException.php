<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException whereMessage($value)
 * @mixin \Eloquent
 */
class ValidationException extends Model
{
    protected $table = 'validation_exception';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'message';

    protected $fillable = [
        'message', 'errorCode'
    ];
}
