<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\InterfaceModel;

/**
 * Address Model
 *
 * Last updated: 2025-07-30 07:31:30
 * Updated by: mulyadafa
 *
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $recipient
 * @property string $phone_number
 * @property string $full_address
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $postal_code
 * @property bool $is_primary
 * @property int $interface_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read InterfaceModel|null $interface
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereFullAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLongitude($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'label',
        'recipient',
        'phone_number',
        'full_address',
        'address',
        'city',
        'state',
        'zip_code',
        'postal_code',
        'is_primary',
        'interface_id',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the user that owns the address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the interface that the address belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }
}
