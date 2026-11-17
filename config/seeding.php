<?php

return [
    'user_password' => env('SEED_USER_PASSWORD', 'pass12345'),
    'regions' => [
        'directory' => database_path('data/regions'),
        'supplement' => database_path('data/regions/kodepos-papua-barat-daya.csv'),
    ],
];
