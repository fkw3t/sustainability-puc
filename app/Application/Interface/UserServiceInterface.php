<?php

declare(strict_types=1);

namespace App\Application\Interface;

use App\Application\DTO\User\Request\AuthenticateRequestDTO;
use App\Application\DTO\User\Request\RegisterUserRequestDTO;
use App\Application\DTO\User\Structure\AuthenticateResponseDTO;
use App\Domain\Entity\User;

interface UserServiceInterface
{
    public function register(RegisterUserRequestDTO $dto): string;
    public function authenticate(AuthenticateRequestDTO $dto): AuthenticateResponseDTO;

    public function findUser(string $id): ?User;
}
