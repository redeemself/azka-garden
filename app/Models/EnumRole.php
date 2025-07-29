<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $value
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereValue($value)
 * @mixin \Eloquent
 */
class EnumRole extends Model
{
    protected $table = 'enum_roles';

    // Jika tabel enum_roles ada kolom created_at dan updated_at, ini harus true
    public $timestamps = true;

    protected $fillable = [
        'value',
        'description',
    ];

    /**
     * Relasi ke Role (asumsi foreign key enum_role_id di tabel roles)
     */
    public function roles()
    {
        return $this->hasMany(Role::class, 'enum_role_id');
    }
}
