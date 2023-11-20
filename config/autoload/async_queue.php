<?php

declare(strict_types=1);

return [
    'default' => [
        'driver'        => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'channel'       => 'queue',
        'retry_seconds' => 6,
        'processes'     => 1,
    ],
];
