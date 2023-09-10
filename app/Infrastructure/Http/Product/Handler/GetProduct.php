<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Handler;

use App\Application\DTO\ExternalProductManager\Request\GetProductRequestDTO;
use App\Application\Exception\ProductNotFoundException;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Application\Interface\ProductServiceInterface;
use App\Infrastructure\Http\Product\Request\GetProductRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

final class GetProduct
{
    private LoggerInterface $logger;

    public function __construct(
        private ExternalProductManagerServiceInterface $externalProductManagerService,
        private ProductServiceInterface $productService,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @param GetProductRequest $request
     * @param ResponseInterface $response
     * @return PsrResponseInterface
     */
    public function handle(GetProductRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        try {
            $productRequestDTO = new GetProductRequestDTO(
                $request->input('searchable_field'),
                $request->input('value')
            );

            match ($productRequestDTO->searchableFieldType) {
                'barcode' => $responseDTO = $this->productService->getProductByBarcode($productRequestDTO->searchableFieldValue),
                'name'    => $responseDTO = $this->externalProductManagerService->listProductsByName($productRequestDTO->searchableFieldValue),
            };

            return $response->json($responseDTO->toArray())
                ->withStatus(ResponseCode::HTTP_OK);
        } catch (ProductNotFoundException $exception) {
            return $response->json(
                ['message' => $exception->getMessage()]
            )->withStatus($exception->getCode());
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
                'trace'   => $exception->getTraceAsString(),
            ]);

            return $response->json(['message' => 'Something went wrong'])
                ->withStatus(ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
