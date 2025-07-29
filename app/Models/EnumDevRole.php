<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevRole> $devRoles
 * @property-read int|null $dev_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole whereValue($value)
 * @mixin \Eloquent
 */
class EnumDevRole extends Model
{
    protected $table = 'enum_dev_role';
    public $timestamps = false;

    protected $fillable = ['value'];

    public function devRoles()
    {
        return $this->hasMany(DevRole::class, 'enum_dev_role_id');
    }
}
