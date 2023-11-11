<?php

declare(strict_types=1);

namespace App\Application\DTO\ExternalProductManager\Request;

use App\Application\DTO\DTO;

class GetProductRequestDTO extends DTO
{
    public function __construct(
        public string $searchableFieldType,
        public string $searchableFieldValue
    ) {
    }
}
