<?php

declare(strict_types=1);

namespace App\Application\DTO\Product\Structure;

use App\Application\DTO\DTO;

final class UserRegisteredProductsFromExpireDateResponseDTO extends DTO
{
    public function __construct(
        /* @var $expiresWithinWeek UserRegisteredProductDTO[] */
        public array $expiresWithinWeek,
        /* @var $expiresBetweenOneAndTwoWeeks UserRegisteredProductDTO[] */
        public array $expiresBetweenOneAndTwoWeeks,
        /* @var $expiresMoreThanTwoWeeks UserRegisteredProductDTO[] */
        public array $expiresMoreThanTwoWeeks,
    ) {
    }
}
