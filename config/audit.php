<?php

return [
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'token',
        '_token',
        'authorization',
        'cookie',
        'secret',
        'account_number',
        'nik',
    ],

    'ignored_fields' => [
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
    ],
];
