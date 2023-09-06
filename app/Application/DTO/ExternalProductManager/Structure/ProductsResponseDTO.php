<?php

namespace App\Application\DTO\ExternalProductManager\Structure;

use App\Application\DTO\DTO;

final class ProductsResponseDTO extends DTO
{
    public function __construct(
        /* @var $products ProductResponseDTO */
        public array $products,
    ) {
    }
}