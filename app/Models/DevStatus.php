<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $enum_dev_status_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Developer> $developers
 * @property-read int|null $developers_count
 * @property-read \App\Models\EnumDevStatus $enumStatus
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus whereEnumDevStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus whereId($value)
 * @mixin \Eloquent
 */
class DevStatus extends Model
{
    protected $table = 'dev_statuses';
    public $timestamps = false;

    protected $fillable = ['enum_dev_status_id'];

    public function enumStatus()
    {
        return $this->belongsTo(EnumDevStatus::class, 'enum_dev_status_id');
    }

    public function developers()
    {
        return $this->hasMany(Developer::class, 'status_id');
    }
}
