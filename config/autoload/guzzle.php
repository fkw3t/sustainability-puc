<?php

declare(strict_types=1);
use function Hyperf\Support\env;

return [
    // @todo: add query string token parameter middleware ?=key=env(token)
    'barcode-manager' => [
        'client' => [
            'base_uri'        => env('BARCODE_MANAGER_URL'),
            'timeout'         => (int) env('BARCODE_MANAGER_TIMEOUT'),
            'connect_timeout' => (int) env('BARCODE_MANAGER_TIMEOUT'),
            'http_errors'     => false,
        ],
    ],
];
