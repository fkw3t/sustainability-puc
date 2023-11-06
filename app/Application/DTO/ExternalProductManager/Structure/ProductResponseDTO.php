<?php

declare(strict_types=1);

namespace App\Application\DTO\ExternalProductManager\Structure;

use App\Application\DTO\DTO;

final class ProductResponseDTO extends DTO
{
    public function __construct(
        public string $barcode,
        public string $name,
        public ?string $description,
        public ?string $brand,
        public ?float $averagePrice,
        public ?string $imageUrl
    ) {
    }
}
