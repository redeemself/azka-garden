<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $ticket_number
 * @property string|null $category
 * @property string|null $subject
 * @property string|null $description
 * @property string|null $status
 * @property string|null $priority
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerSupport extends Model
{
    protected $table = 'customer_support';
    public $timestamps = false;

    protected $fillable = [
        'customer_id', 'ticket_number',
        'category', 'subject',
        'description', 'status',
        'priority'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
