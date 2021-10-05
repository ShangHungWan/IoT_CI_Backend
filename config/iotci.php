<?php

return [
    'analysis' => [
        'status' => [
            'SUCCESS' => 'success',
            'TESTING' => 'testing',
            'EMULATION_FAILED' => 'emulation_failed',
            'COMPILATION_FAILED' => 'compilation_failed',
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
        'STATIC_ANALYSIS_SHELL' => '/home/ubuntu/IoT_CI_Static_Analysis/firmae_demo.sh',
        'LOG_FOLDER' => '/home/ubuntu/Logs',
    ],
];
