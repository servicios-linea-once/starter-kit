<?php

return [
    'auth' => [
        'registration' => env('SLO_AUTH_REGISTRATION', true),

        'two_factor' => [
            'enabled' => env('SLO_AUTH_TWO_FACTOR', true),
            'required' => false,
            'issuer' => env('APP_NAME', 'Servicios Linea Once'),
            'window' => 1,
            'recovery_codes' => 8,
        ],
    ],
];
