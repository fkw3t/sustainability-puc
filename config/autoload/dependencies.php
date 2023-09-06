<?php

declare(strict_types=1);

return [
    // barcode-manager
    \App\Application\Interface\BarcodeManagerServiceInterface::class => \App\Application\Service\BarcodeManagerService::class,
    \App\Domain\Repository\BarcodeManagerRepositoryInterface::class  => \App\Infrastructure\Repository\BarcodeManagerRepository::class,
];
