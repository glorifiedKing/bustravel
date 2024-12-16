<?php

return [
    'path'       => 'transit',
    'user_model' => App\User::class,
    'payment_gateways' => [
        'mtn_rw' => [
            'username' => 'palm_kash',
            'password' => '12345',
            'url' => 'https://mtn.co.rw:8080',
        ],
    ],
    'gateway_jwt_token' => env('PALMKASH_GATEWAY_JWT', '123456'),
];
