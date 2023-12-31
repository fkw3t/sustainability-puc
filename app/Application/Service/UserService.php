<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\User\Request\AuthenticateRequestDTO;
use App\Application\DTO\User\Request\RegisterUserRequestDTO;
use App\Application\DTO\User\Structure\AuthenticateResponseDTO;
use App\Application\Exception\InvalidCredentialsException;
use App\Application\Exception\User\AlreadyRegisteredUser;
use App\Application\Exception\UserNotFoundException;
use App\Application\Interface\UserServiceInterface;
use App\Application\Mapper\UserMapper;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Firebase\JWT\JWT;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use function Hyperf\Support\env;

final readonly class UserService implements UserServiceInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private UserRepositoryInterface $repository,
        private UserMapper $mapper,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @throws AlreadyRegisteredUser
     */
    public function register(RegisterUserRequestDTO $dto): string
    {
        if ($this->repository->hasRegister(
            $user = $this->mapper->transformRegisterRequestDTOToEntity($dto)
        )) {
            throw new AlreadyRegisteredUser();
        }

        $user->password = password_hash($user->password, PASSWORD_BCRYPT);
        $this->repository->create($user);

        return JWT::encode(
            ['email' => $user->email],
            env('JWT_SECRET_KEY'),
            'HS256'
        );
    }

    /**
     * @throws InvalidCredentialsException
     * @throws UserNotFoundException
     */
    public function authenticate(AuthenticateRequestDTO $dto): AuthenticateResponseDTO
    {
        if (! $user = $this->repository->findByEmail($dto->email)) {
            throw new UserNotFoundException();
        }

        if (! password_verify($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return new AuthenticateResponseDTO(
            $user->id,
            $user->name,
            $user->email,
            JWT::encode(['email' => $user->email], env('JWT_SECRET_KEY'),'HS256')
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUser(string $id): ?User
    {
        return $this->repository->find($id) ?? throw new UserNotFoundException();
    }
}
