<?php

declare(strict_types=1);

return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            \App\Application\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
