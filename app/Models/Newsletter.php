<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $content
 * @property string|null $recipient_type
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereSubject($value)
 * @mixin \Eloquent
 */
class Newsletter extends Model
{
    protected $table = 'newsletters';
    public $timestamps = false;

    protected $fillable = [
        'subject', 'content',
        'recipient_type', 'status',
        'scheduled_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
