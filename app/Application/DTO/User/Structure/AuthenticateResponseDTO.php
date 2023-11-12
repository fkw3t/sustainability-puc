<?php

declare(strict_types=1);

namespace App\Application\DTO\User\Structure;

use App\Application\DTO\DTO;

class AuthenticateResponseDTO extends DTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $token,
    ) {
    }
}
