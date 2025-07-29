<?php
// app/Models/InterfaceModel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InterfaceMethod;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InterfaceMethod> $methods
 * @property-read int|null $methods_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InterfaceModel extends Model
{
    // Nama tabel di database
    protected $table = 'interfaces';

    // Kita tidak punya kolom created_at / updated_at di migration ini
    public $timestamps = false;

    // Kolom yang bisa di‐mass assign
    protected $fillable = ['name', 'description'];

    /**
     * Relasi ke method‐method di interface ini.
     */
    public function methods()
    {
        return $this->hasMany(InterfaceMethod::class, 'interface_id');
    }
}
