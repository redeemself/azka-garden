<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard dan password broker untuk aplikasi.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),                 // Default guard: web (User)
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'), // Default password broker: users
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Definisikan guard untuk User, Admin, dan Developer.
    | Masing-masing guard menggunakan session driver dan provider terkait.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',                           // Guard untuk User biasa
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',                          // Guard untuk Admin
        ],

        'developer' => [
            'driver' => 'session',
            'provider' => 'developers',                      // Guard untuk Developer
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Definisikan provider model untuk masing-masing tipe user.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,               // Model User biasa
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,              // Model Admin
        ],

        'developers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Developer::class,          // Model Developer
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi reset password untuk masing-masing tipe user.
    | Bisa gunakan tabel reset token berbeda sesuai kebutuhan.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => env('AUTH_ADMIN_PASSWORD_RESET_TABLE', 'admin_password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'developers' => [
            'provider' => 'developers',
            'table' => env('AUTH_DEV_PASSWORD_RESET_TABLE', 'developer_password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Waktu (dalam detik) timeout untuk konfirmasi ulang password.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
