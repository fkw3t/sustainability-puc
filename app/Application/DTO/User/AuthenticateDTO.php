<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

use App\Application\DTO\DTO;

class AuthenticateDTO extends DTO
{
    protected static array $hidden = [
        'password',
    ];

    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
