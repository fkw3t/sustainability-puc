<?php

declare(strict_types=1);
use function Hyperf\Support\env;

return [
    'some-service' => [
        'client' => [
            'base_url'        => env('SOMESERVICE_URL'),
            'timeout'         => env('SOMESERVICE_TIMEOUT'),
            'connect_timeout' => env('SOMESERVICE_TIMEOUT'),
            'http_errors'     => false,
        ],
    ],
    'pokemon' => [
        'client' => [
            'base_uri'        => 'https://pokeapi.co/api/v2/',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'http_errors'     => false,
        ],
    ],
];
