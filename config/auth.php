<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'colaboradores'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'colaboradores',
        ],
    ],

    'providers' => [
        'colaboradores' => [
            'driver' => 'eloquent',
            'model' => App\Models\Colaborador::class,
        ],
    ],

    'passwords' => [
        'colaboradores' => [
            'provider' => 'colaboradores',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
