<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int    $id          <-- tambahkan baris ini
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int    $role_id
 * @property int    $status_id
 * @property int    $interface_id
 * @property \Illuminate\Support\Carbon|null $last_login
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username', 'name', 'email', 'password',
        'role_id', 'status_id', 'interface_id', 'last_login',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    // Optional: method isAdmin() jika dipanggil di middleware
    public function isAdmin()
    {
        // Implementasi: misal return $this->role_id == ...;
        return true;
    }
}
