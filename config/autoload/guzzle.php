<?php

declare(strict_types=1);
use function Hyperf\Support\env;

return [
    'barcode-manager' => [
        'client' => [
            'base_uri'        => env('EXTERNAL_PRODUCT_MANAGER_URL'),
            'timeout'         => (int) env('EXTERNAL_PRODUCT_MANAGER_TIMEOUT'),
            'connect_timeout' => (int) env('EXTERNAL_PRODUCT_MANAGER_TIMEOUT'),
            'http_errors'     => false,
            'headers'         => [
                'X-Cosmos-Token' => env('EXTERNAL_PRODUCT_MANAGER_AUTH_KEY'),
            ],
        ],
    ],
];
