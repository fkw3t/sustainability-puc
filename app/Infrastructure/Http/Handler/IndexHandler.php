<?php

declare(strict_types=1);
namespace App\Infrastructure\Http\Handler;

use Hyperf\HttpServer\Contract\{RequestInterface, ResponseInterface};

class IndexHandler
{
    public function handle(RequestInterface $request, ResponseInterface $response)
    {
        return [
            'method' => $request->getMethod(),
            'message' => "Hello {$request->input('user', 'Hyperf')}.",
        ];
    }
}
