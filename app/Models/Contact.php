<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @mixin \Eloquent
 */
class Contact extends Model
{
    protected $table = 'contacts';  // pastikan migrasi 'contacts' ada

    protected $fillable = [
        'name',
        'email',
        'phone',    // tambahkan phone supaya bisa diisi mass assignment
        'message',
        'promo_code', // tambahkan promo_code agar bisa diisi mass assignment
    ];
}
