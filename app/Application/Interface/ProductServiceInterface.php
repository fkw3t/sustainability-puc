<?php

namespace App\Application\Interface;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;

interface ProductServiceInterface
{
    public function getProductByBarcode(string $barcode): ProductResponseDTO;

    public function create(ProductResponseDTO $product): bool;
}