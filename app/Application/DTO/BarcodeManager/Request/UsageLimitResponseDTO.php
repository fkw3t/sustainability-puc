<?php

declare(strict_types=1);
namespace App\Application\DTO\BarcodeManager\Request;

use App\Application\DTO\DTO;

class UsageLimitResponseDTO extends DTO
{
    protected static array $validateRules = [];

    public function __construct(
        public int $allowedCallsMonth,
        public int $remainingCallsMonth,
        public int $allowedCallsMinute,
        public int $remainingCallsMinute,
    ) {
    }
}
