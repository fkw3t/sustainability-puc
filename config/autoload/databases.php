<?php

declare(strict_types=1);

use function Hyperf\Support\env;

return [
    'default' => [
        'driver'    => env('DB_DRIVER', 'mysql'),
        'host'      => env('DB_HOST', 'localhost'),
        'database'  => env('DB_DATABASE', 'hyperf'),
        'port'      => env('DB_PORT', 3306),
        'username'  => env('DB_USERNAME', 'root'),
        'password'  => env('DB_PASSWORD', ''),
        'charset'   => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix'    => env('DB_PREFIX', ''),
        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout'    => 3.0,
            'heartbeat'       => -1,
            'max_idle_time'   => (float) env('DB_MAX_IDLE_TIME', 60),
        ],
        'commands' => [
            'gen:model' => [
                'path'        => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
            ],
        ],
    ],
];
