<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findByBarcode(string $barcode): ?Product;

    public function create(Product $product): bool;
}
