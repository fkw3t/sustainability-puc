<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

final class ProductsResponseDTO extends DTO
{
    public function __construct(
        /* @var $products ProductResponseDTO */
        public array $products,
    ) {
    }
}
