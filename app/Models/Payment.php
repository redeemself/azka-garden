<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $order_id
 * @property int $method_id
 * @property string $transaction_code
 * @property string|null $bank_account
 * @property numeric $total
 * @property int $enum_payment_status_id
 * @property string|null $proof_of_payment
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\PaymentMethod $method
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\EnumPaymentStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereEnumPaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProofOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id',
        'method_id',
        'transaction_code',
        'bank_account',
        'total',
        'enum_payment_status_id',
        'proof_of_payment',
        'expired_at'
    ];

    protected $casts = [
        'total'      => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    public function status()
    {
        return $this->belongsTo(EnumPaymentStatus::class, 'enum_payment_status_id');
    }
}
