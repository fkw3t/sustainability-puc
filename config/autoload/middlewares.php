<?php

declare(strict_types=1);

return [
    'http' => [
        \Gokure\HyperfCors\CorsMiddleware::class,
        \Hyperf\Validation\Middleware\ValidationMiddleware::class,
    ],
];
