<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $status
 * @property string|null $findings
 * @property int|null $developer_id
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereFindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereType($value)
 * @mixin \Eloquent
 */
class SecurityAudit extends Model
{
    protected $table = 'security_audits';
    public $timestamps = false;

    protected $fillable = [
        'type', 'description',
        'severity', 'status',
        'findings', 'developer_id'
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
