<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Document;
use DateTimeInterface;

final class User
{
    public function __construct(
        public ?string $id,
        public string $name,
        public Document $document,
        public string $email,
        public string $password,
        public DateTimeInterface $birthdate,
    ) {
    }
}
