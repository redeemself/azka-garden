<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $enum_dev_role_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Developer> $developers
 * @property-read int|null $developers_count
 * @property-read \App\Models\EnumDevRole $enumRole
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole whereEnumDevRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole whereId($value)
 * @mixin \Eloquent
 */
class DevRole extends Model
{
    protected $table = 'dev_roles';
    public $timestamps = false;

    protected $fillable = ['enum_dev_role_id'];

    public function enumRole()
    {
        return $this->belongsTo(EnumDevRole::class, 'enum_dev_role_id');
    }

    public function developers()
    {
        return $this->hasMany(Developer::class, 'role_id');
    }
}
