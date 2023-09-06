<?php

declare(strict_types=1);
namespace App\Application\DTO\BarcodeManager\Request;

use App\Application\DTO\DTO;

class GetProductRequestDTO extends DTO
{
    protected static array $validateRules = [];

    public function __construct(
        public string $searchableFieldType,
        public string $searchableFieldValue
    ) {
    }
}
