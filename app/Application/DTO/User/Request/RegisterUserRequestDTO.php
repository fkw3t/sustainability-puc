<?php

declare(strict_types=1);

namespace App\Application\DTO\User\Request;

use App\Application\DTO\DTO;
use DateTimeInterface;

class RegisterUserRequestDTO extends DTO
{
    protected static array $hidden = [
        'document',
        'password',
    ];

    public function __construct(
        public string $name,
        public string $document,
        public string $email,
        public string $password,
        public DateTimeInterface $birthdate,
    ) {
    }
}
