<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\InterfaceModel;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $recipient
 * @property string $phone_number
 * @property string $full_address
 * @property string $city
 * @property string $zip_code
 * @property bool $is_primary
 * @property int $interface_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read InterfaceModel $interface
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
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLongitude($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'label',
        'recipient',
        'phone_number',
        'full_address',
        'city',
        'zip_code',
        'is_primary',
        'interface_id', // jika memang ada kolom ini di tabel
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Relasi many-to-one ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-one ke InterfaceModel (jika diperlukan).
     */
    public function interface()
    {
        return $this->belongsTo(InterfaceModel::class, 'interface_id');
    }

    /**
     * Accessor for latitude.
     */
    public function getLatitudeAttribute($value)
    {
        return $value;
    }

    /**
     * Accessor for longitude.
     */
    public function getLongitudeAttribute($value)
    {
        return $value;
    }
}