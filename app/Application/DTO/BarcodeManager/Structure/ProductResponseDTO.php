<?php

namespace App\Application\DTO\BarcodeManager\Structure;

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
