<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

final class UserRegisteredProductDTO extends DTO
{
    public function __construct(
        public string $barcode,
        public string $name,
        public ?string $description,
        public ?string $brand,
        public ?float $averagePrice,
        public ?string $imageUrl,
        public string $expireDate,
        public int $quantity,
        public int $daysUntilExpiry
    ) {
    }
}
