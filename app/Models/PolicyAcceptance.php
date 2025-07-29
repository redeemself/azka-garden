<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $policy_version
 * @property string $accepted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance wherePolicyVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereUserId($value)
 * @mixin \Eloquent
 */
class PolicyAcceptance extends Model
{
    protected $fillable = [
        'user_id',
        'policy_name',
        'accepted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
