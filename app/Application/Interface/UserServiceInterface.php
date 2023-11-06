<?php

declare(strict_types=1);

namespace App\Application\Interface;

use App\Application\DTO\User\AuthenticateDTO;
use App\Application\DTO\User\RegisterUserRequestDTO;

interface UserServiceInterface
{
    public function register(RegisterUserRequestDTO $dto): string;

    public function authenticate(AuthenticateDTO $dto): string;
}
