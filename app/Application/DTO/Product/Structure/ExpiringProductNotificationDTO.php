<?php

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

class ExpiringProductNotificationDTO extends DTO
{
    public function __construct(
        public string $userName,
        public string $userEmail,
        public string $productName,
        public ?string $productImageUrl,
        public string $productExpireDate,
        public int $productQuantity,
        public int $productDaysUntilExpiry
    ) {
    }
}