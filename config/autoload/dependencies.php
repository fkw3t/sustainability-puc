<?php

declare(strict_types=1);

return [
    // external-product-manager
    \App\Application\Interface\ExternalProductManagerServiceInterface::class => \App\Application\Service\ExternalProductManagerService::class,
    \App\Domain\Repository\ExternalProductManagerRepositoryInterface::class  => \App\Infrastructure\Repository\ExternalProductManagerRepository::class,
];
