<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\User\Handler;

use App\Application\DTO\User\AuthenticateDTO;
use App\Application\Exception\InvalidCredentialsException;
use App\Application\Exception\UserNotFoundException;
use App\Application\Interface\UserServiceInterface;
use App\Infrastructure\Http\User\Request\AuthenticateRequest;
use Exception;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

final class AuthenticateUser
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly UserServiceInterface $service,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @OA\Post(
     *     path="/api/user/authenticate",
     *     summary="authenticate",
     *     tags={"user"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="cleia@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=400, description="Bad request"),
     * )
     */
    public function handle(AuthenticateRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        try {
            $authenticateDTO = new AuthenticateDTO(
                $request->input('email'),
                $request->input('password'),
            );

            return $response->json(['token' => $this->service->authenticate($authenticateDTO)])
                ->withStatus(ResponseCode::HTTP_OK);
        } catch (InvalidCredentialsException|UserNotFoundException $exception) {
            return $response->json([
                'message' => $exception->getMessage(),
            ])->withStatus($exception->getCode());
        } catch (Throwable|Exception $exception) {
            $this->logger->error(__CLASS__ . __FUNCTION__, [
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
                'trace'   => $exception->getTraceAsString(),
            ]);

            return $response->json(['message' => 'Something went wrong'])
                ->withStatus(
                    $exception->getCode() > 599
                        ? ResponseCode::HTTP_INTERNAL_SERVER_ERROR
                        : $exception->getCode()
                );
        }
    }
}
