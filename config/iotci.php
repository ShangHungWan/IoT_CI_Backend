<?php

return [
    'analysis' => [
        'status' => [
            'SUCCESS' => 'success',
            'TESTING' => 'testing',
            'EMULATE_FAILED' => 'emulation_failed',
            'COMPILE_FAILED' => 'compilation_failed',
            'UPLOADED' => 'uploaded',
        ],
    ],

    'exploits_logs' => [
        'status' => [
            'INVULNERABLE' => 'invulnerable',
            'VULNERABLE' => 'vulnerable',
            'UNABLE_VERIFIED' => 'unable_verified',
            'UNDEFINED' => 'undefined',
        ],
    ],

    'users' => [
        'type' => [
            'USER' => 'user',
            'ADMIN' => 'admin',
        ],
    ],

    'path' => [
        'STATIC_ANALYSIS' => '',
    ],
];
