<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $db_name
 * @property string|null $backup_type
 * @property string|null $file_path
 * @property int|null $size
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereBackupType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereDbName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereSize($value)
 * @mixin \Eloquent
 */
class DatabaseBackup extends Model
{
    protected $table = 'database_backups';
    public $timestamps = false;

    protected $fillable = [
        'db_name', 'backup_type',
        'file_path', 'size'
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
