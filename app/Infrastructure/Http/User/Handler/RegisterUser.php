<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\User\Handler;

use App\Application\DTO\User\RegisterUserRequestDTO;
use App\Application\Exception\User\AlreadyRegisteredUser;
use App\Application\Interface\UserServiceInterface;
use App\Domain\Exception\InvalidDocumentException;
use App\Infrastructure\Http\User\Request\RegisterUserRequest;
use DateTime;
use Exception;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Throwable;

final class RegisterUser
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
     *     path="/api/user/register",
     *     summary="registration",
     *     tags={"user"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Cleia Souza Fonseca"),
     *             @OA\Property(property="document", type="string", example="93709123046"),
     *             @OA\Property(property="email", type="string", format="email", example="cleia@mail.com"),
     *             @OA\Property(property="password", type="string", example="1234578"),
     *             @OA\Property(property="birthdate", type="string", example="1990-01-20")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=409, description="User already registered"),
     *     @OA\Response(response=422, description="Invalid body parameter"),
     * )
     */
    public function handle(RegisterUserRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        try {
            $productRequestDTO = new RegisterUserRequestDTO(
                $request->input('name'),
                $request->input('document'),
                $request->input('email'),
                $request->input('password'),
                DateTime::createFromFormat('Y-m-d', $request->input('birthdate')),
            );

            return $response->json(['token' => $this->service->register($productRequestDTO)])
                ->withStatus(ResponseCode::HTTP_CREATED);
        } catch (InvalidDocumentException|AlreadyRegisteredUser $exception) {
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
