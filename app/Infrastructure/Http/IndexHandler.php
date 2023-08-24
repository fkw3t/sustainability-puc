<?php

declare(strict_types=1);
namespace App\Infrastructure\Http;

use Hyperf\HttpServer\Contract\{RequestInterface, ResponseInterface};

class IndexHandler
{
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        return [
            'hello word with Hyperf',
        ];
    }
}
