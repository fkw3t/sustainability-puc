<?php

declare(strict_types=1);

namespace App\Application\DTO\ExternalProductManager\Request;

use App\Application\DTO\DTO;
use DateTimeInterface;

class AssignProductRequestDTO extends DTO
{
    public function __construct(
        public string $userId,
        public string $productBarcode,
        public DateTimeInterface $expireDate,
        public int $quantity
    ) {
    }
}
