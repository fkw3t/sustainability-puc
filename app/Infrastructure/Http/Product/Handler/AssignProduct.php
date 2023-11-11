<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Handler;

use App\Application\DTO\ExternalProductManager\Request\AssignProductRequestDTO;
use App\Application\DTO\ExternalProductManager\Request\GetProductRequestDTO;
use App\Application\Exception\Product\ProductNotFoundException;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Application\Interface\ProductServiceInterface;
use App\Infrastructure\Http\Product\Request\AssignProductRequest;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

final class AssignProduct
{
    private LoggerInterface $logger;

    public function __construct(
        private ProductServiceInterface $productService,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @OA\Post(
     *     path="/api/product/assign",
     *     summary="assign product to a user",
     *     tags={"product"},
     *     security={{"auth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="string", example="837ad4e7-b401-4406-a0b0-fe1f3016ca0b"),
     *             @OA\Property(property="product_barcode", type="string", example="7896070511019"),
     *             @OA\Property(property="expire_date", type="string", example="2023-12-24"),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Product/user not found"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=422, description="Unprocessable request"),
     * )
     */
    public function handle(AssignProductRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        try {
            $productRequestDTO = new AssignProductRequestDTO(
                $request->input('user_id'),
                $request->input('product_barcode'),
                $request->input('expire_date'),
                $request->input('quantity')
            );

            $this->productService->assign($productRequestDTO);

            return $response->json([])
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
        } catch (Throwable|Exception $exception) {
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
