<?php

declare(strict_types=1);

namespace App\Application\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response): MessageInterface|ResponseInterface
    {
        $this->stopPropagation();

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(Response::HTTP_UNAUTHORIZED)
            ->withBody(
                new SwooleStream(
                    json_encode(
                        [
                            'message' => $throwable->getMessage(),
                        ],
                        JSON_UNESCAPED_UNICODE
                    )
                )
            );
    }

    public function isValid(Throwable $throwable): bool
    {
        if ($throwable instanceof UnauthorizedHttpException) {
            return true;
        }

        return false;
    }
}
