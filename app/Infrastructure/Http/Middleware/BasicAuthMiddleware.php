<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

use function Hyperf\Support\env;

class BasicAuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! array_key_exists(0, $request->getHeader('Authorization'))) {
            throw new UnauthorizedHttpException('Unauthorized request', Response::HTTP_UNAUTHORIZED);
        }

        $header = $request->getHeader('Authorization')[0];

        if (! $this->jwtDecode($header) || empty($header)) {
            throw new UnauthorizedHttpException('Unauthorized request', Response::HTTP_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }

    private function jwtDecode(string $token): bool
    {
        try {
            $token = trim(str_replace('Bearer ', '', $token));
            JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
