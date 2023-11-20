<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

class ExpiringProductsNotificationDTO extends DTO
{
    public function __construct(
        /* @var $products ExpiringProductNotificationDTO[] */
        public array $expiringProducts,
    ) {
    }
}
