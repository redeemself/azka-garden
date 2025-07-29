<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $subscriber_id
 * @property string $email
 * @property string $subscribed_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereSubscribedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereSubscriberId($value)
 * @mixin \Eloquent
 */
class Subscriber extends Model
{
    //
}
