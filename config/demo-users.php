<?php

return [
    'users' => [
        [
            'name' => 'Elon Musk',
            'user_name' => 'super_user',
            'email' => 'elon@musk.com',
        ],
        [
            'name' => 'mark juckerberg',
            'user_name' => 'm_jocker',
            'email' => 'mj@facebook.com',
        ],
        [
            'name' => 'angelo mathews',
            'user_name' => 'mathews_sl',
            'email' => 'mathews@timeout.com',
        ],
    ],
    'system_password' => env('SYSTEM_USER_PASSWORD', 'password'),
];
