<?php

return [
    'analysis' => [
        'status' => [
            'SUCCESS' => 'success',
            'TESTING' => 'testing',
            'FAILED' => 'failed',
            'N/A' => 'n/a',
        ],
    ],

    'exploits_logs' => [
        'status' => [
            'INVULNERABLE' => 'invulnerable',
            'VULNERABLE' => 'vulnerable',
            'UNABLE_VERIFIED' => 'unable_verified',
            'UNDEFINED' => 'undefined',
            'N/A' => 'n/a',
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
        'P2IM_SHELL' => '/home/ubuntu/p2im/Script.py',
        'LOG_FOLDER' => '/home/ubuntu/Logs',
    ],
];
