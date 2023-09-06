<?php

declare(strict_types=1);

namespace App\Application\Interface;

use App\Application\DTO\BarcodeManager\Structure\ProductResponseDTO;
use App\Application\DTO\BarcodeManager\Structure\ProductsResponseDTO;

interface BarcodeManagerServiceInterface
{
    public function getProductByBarcode(string $barcode): ProductResponseDTO;

    public function listProductsByName(string $name): ProductsResponseDTO;
}
