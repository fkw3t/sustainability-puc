<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Request;

use App\Application\DTO\DTO;
use App\Application\DTO\Product\Structure\ProductResponseDTO;
use DateTimeInterface;

class AssignProductRequestDTO extends DTO
{
    public function __construct(
        public string $userId,
        public ProductResponseDTO $productResponseDTO,
        public DateTimeInterface $expireDate,
        public int $quantity
    ) {
    }
}
