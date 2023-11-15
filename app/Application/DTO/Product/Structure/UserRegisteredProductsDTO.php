<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

final class UserRegisteredProductsDTO extends DTO
{
    public function __construct(
        /* @var $products UserRegisteredProductDTO */
        public array $registeredProducts,
    ) {
    }
}
