<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevStatus> $devStatuses
 * @property-read int|null $dev_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus whereValue($value)
 * @mixin \Eloquent
 */
class EnumDevStatus extends Model
{
    protected $table = 'enum_dev_status';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function devStatuses()
    {
        return $this->hasMany(DevStatus::class, 'enum_dev_status_id');
    }
}
