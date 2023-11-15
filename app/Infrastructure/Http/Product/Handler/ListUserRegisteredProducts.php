<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Product\Handler;

use App\Application\Exception\Product\ProductNotFoundException;
use App\Application\Exception\UserNotFoundException;
use App\Application\Interface\ProductServiceInterface;
use App\Domain\Exception\InvalidExpireDateException;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

final class ListUserRegisteredProducts
{
    private LoggerInterface $logger;

    public function __construct(
        private ProductServiceInterface $productService,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @OA\Get(
     *     path="/api/product/user/{id}",
     *     summary="List user products",
     *     tags={"product"},
     *     security={{"auth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="user id",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=422, description="Unprocessable request"),
     * )
     */
    public function handle(RequestInterface $request, string $id, ResponseInterface $response): PsrResponseInterface
    {
        try {
            return $response->json($this->productService->listUserRegisteredProducts($id)->toArray())
                ->withStatus(ResponseCode::HTTP_OK);
        } catch (ProductNotFoundException|UserNotFoundException|InvalidExpireDateException $exception) { // TODO: create business/infra exception
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
