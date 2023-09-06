<?php

declare(strict_types=1);

namespace App\Application\Interface;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Application\DTO\ExternalProductManager\Structure\ProductsResponseDTO;

interface ExternalProductManagerServiceInterface
{
    public function getProductByBarcode(string $barcode): ProductResponseDTO;

    public function listProductsByName(string $name): ProductsResponseDTO;
}
