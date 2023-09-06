<?php

declare(strict_types=1);

namespace App\Infrastructure\Guzzle;

use Psr\Http\Message\RequestInterface;

class QueryStringMiddleware
{
    public function __construct(
        private array $queryParams
    ) {
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            parse_str($request->getUri()->getQuery(), $query);
            foreach ($this->queryParams as $param => $value) {
                $request = $request->withUri($request->getUri()->withQuery(
                    http_build_query(array_merge(
                        $query ?? [],
                        [$param => $value]
                    ))
                ));
            }

            return $handler($request, $options);
        };
    }
}
