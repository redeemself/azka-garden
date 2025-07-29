<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property bool $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SupplierManagement extends Model
{
    protected $table = 'supplier_management';
    public $timestamps = false;

    protected $fillable = [
        'name', 'email',
        'phone', 'address',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
