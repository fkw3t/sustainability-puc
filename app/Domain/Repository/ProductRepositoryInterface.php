<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use DateTimeInterface;

interface ProductRepositoryInterface
{
    public function findByBarcode(string $barcode): ?Product;
    public function create(Product $product): bool;
    public function assign(Product $product, User $user, int $quantity): bool;
    public function findByDate(User $user, DateTimeInterface $startDate, DateTimeInterface $endDate = null): array;
}
