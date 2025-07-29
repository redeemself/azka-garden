<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $component
 * @property string $config_key
 * @property string|null $config_value
 * @property bool $is_encrypted
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereConfigKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereConfigValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereIsEncrypted($value)
 * @mixin \Eloquent
 */
class SecurityConfig extends Model
{
    protected $table = 'security_configs';
    public $timestamps = false;

    protected $fillable = [
        'component', 'config_key',
        'config_value', 'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
