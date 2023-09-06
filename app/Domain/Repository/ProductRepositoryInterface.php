<?php

namespace App\Domain\Repository;

interface ProductRepositoryInterface
{
    public function findByBarcode(string $barcode);
}