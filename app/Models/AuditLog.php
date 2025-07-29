<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $recorded_by
 * @property string|null $action
 * @property string|null $details
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Admin|null $recorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereRecordedBy($value)
 * @mixin \Eloquent
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'recorded_by', 'action', 'details'
    ];

    public function recorder()
    {
        return $this->belongsTo(Admin::class, 'recorded_by');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
