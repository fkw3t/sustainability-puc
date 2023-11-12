<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Application\DTO\Product\Structure\ProductResponseDTO;
use App\Application\DTO\Product\Structure\ProductsResponseDTO;

interface ExternalProductManagerRepositoryInterface
{
    public function getByBarcode(string $barcode): ProductResponseDTO;

    public function listByName(string $name): ProductsResponseDTO;
}
