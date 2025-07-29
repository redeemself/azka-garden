<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $status
 * @property int|null $assigned_to
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $assignee
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereTitle($value)
 * @mixin \Eloquent
 */
class BugReport extends Model
{
    protected $table = 'bug_reports';
    public $timestamps = false;

    protected $fillable = [
        'title', 'description',
        'severity', 'status',
        'assigned_to'
    ];

    public function assignee()
    {
        return $this->belongsTo(Developer::class, 'assigned_to');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
