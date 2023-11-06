<?php

declare(strict_types=1);

return [
    'handler' => [
        'http' => [
            \App\Application\Exception\Handler\ValidationExceptionHandler::class,
            \App\Application\Exception\Handler\AuthExceptionHandler::class,
            \App\Application\Exception\Handler\HttpExceptionHandler::class,
            \App\Application\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
