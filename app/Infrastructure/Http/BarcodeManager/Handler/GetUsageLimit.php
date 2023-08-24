<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\BarcodeManager\Handler;

use App\Domain\Repository\BarcodeManagerRepositoryInterface;
use GuzzleHttp\Exception\ServerException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class GetUsageLimit
{
    public function __construct(
        private BarcodeManagerRepositoryInterface $repository
    ) {
    }

    public function handle(RequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        try {
            $usageLimitDTO = $this->repository->getUsageLimit();
            return $response->json($usageLimitDTO->toArray())
                ->withStatus(200);
        } catch (ServerException $exception) {
            return $response->json([])
                ->withStatus(500);
        }
    }

}
