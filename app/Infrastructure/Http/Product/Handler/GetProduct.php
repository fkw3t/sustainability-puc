<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Handler;

use App\Application\DTO\ExternalProductManager\Request\GetProductRequestDTO;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Infrastructure\Http\Product\Request\GetProductRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;

final class GetProduct
{
    private LoggerInterface $logger;

    public function __construct(
        private ExternalProductManagerServiceInterface $service,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function handle(GetProductRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        try {
            $productRequestDTO = new GetProductRequestDTO(
                $request->input('searchable_field'),
                $request->input('value')
            );

            match ($productRequestDTO->searchableFieldType) {
                'barcode' => $responseDTO = $this->service->getProductByBarcode($productRequestDTO->searchableFieldValue),
                'name'    => $responseDTO = $this->service->listProductsByName($productRequestDTO->searchableFieldValue),
            };

            return $response->json($responseDTO->toArray());
        } catch (ClientException|ServerException $exception) {
            $this->logger->error(__CLASS__ . __FUNCTION__, [
                'message' => $exception->getMessage(),
                'code'    => $exception->getCode(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
            ]);

            return $response->json(
                ['message' => $exception->getMessage()]
            )->withStatus($exception->getCode());
        } catch (\Throwable|\Exception $exception) {
            $this->logger->error(__CLASS__ . __FUNCTION__, [
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
            ]);

            return $response->json(['message' => 'something went wrong'])->withStatus(500);
        }
    }
}
