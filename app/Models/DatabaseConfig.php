<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $db_name
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string|null $password
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereDbName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereUsername($value)
 * @mixin \Eloquent
 */
class DatabaseConfig extends Model
{
    protected $table = 'database_configs';
    public $timestamps = false;

    protected $fillable = [
        'db_name', 'host',
        'port', 'username',
        'password'
    ];

    protected $casts = [
        'port' => 'integer',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
