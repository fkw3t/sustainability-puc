<?php

namespace App\Domain\Repository;

use App\Application\DTO\BarcodeManager\Request\UsageLimitResponseDTO;

interface BarcodeManagerRepositoryInterface
{
    public function getUsageLimit(): UsageLimitResponseDTO;
}