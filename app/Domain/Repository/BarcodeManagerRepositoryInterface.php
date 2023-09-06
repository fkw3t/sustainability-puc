<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Application\DTO\BarcodeManager\Structure\ProductResponseDTO;
use App\Application\DTO\BarcodeManager\Structure\ProductsResponseDTO;

interface BarcodeManagerRepositoryInterface
{
    public function getByBarcode(string $barcode): ProductResponseDTO;

    public function listByName(string $name): ProductsResponseDTO;
}
