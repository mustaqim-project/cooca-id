<?php

return [
    'defaults' => [
        'guard' => 'customer', // Default guard untuk web
        'passwords' => 'customers',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],
        'affiliator' => [
            'driver' => 'session',
            'provider' => 'affiliators',
        ],
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => null, // Menggunakan provider default atau custom jika perlu
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
        'affiliators' => [
            'driver' => 'eloquent',
            'model' => App\Models\Affiliator::class,
        ],
    ],

    'passwords' => [
        'admins' => ['provider' => 'admins', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'customers' => ['provider' => 'customers', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'affiliators' => ['provider' => 'affiliators', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
    ],

    'password_timeout' => 10800,
];
