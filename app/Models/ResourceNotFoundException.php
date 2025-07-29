<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException whereMessage($value)
 * @mixin \Eloquent
 */
class ResourceNotFoundException extends Model
{
    protected $table = 'resource_not_found_exception';
    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'message';

    protected $fillable = [
        'message', 'errorCode'
    ];
}
