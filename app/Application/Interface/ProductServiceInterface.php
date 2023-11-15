<?php

declare(strict_types=1);

namespace App\Application\Interface;

use App\Application\DTO\Product\Request\AssignProductRequestDTO;
use App\Application\DTO\Product\Structure\ProductResponseDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductsFromExpireDateResponseDTO;

interface ProductServiceInterface
{
    public function getProductByBarcode(string $barcode): ProductResponseDTO;
    public function create(ProductResponseDTO $product): bool;
    public function assign(AssignProductRequestDTO $assignDTO): bool;
    public function listUserRegisteredProducts(string $userId): UserRegisteredProductsFromExpireDateResponseDTO;
}
