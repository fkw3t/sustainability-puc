<?php

declare(strict_types=1);

return [
    // external-product-manager
    \App\Application\Interface\ExternalProductManagerServiceInterface::class => \App\Application\Service\ExternalProductManagerService::class,
    \App\Domain\Repository\ExternalProductManagerRepositoryInterface::class  => \App\Infrastructure\Repository\ExternalProductManagerRepository::class,

    // product
    \App\Application\Interface\ProductServiceInterface::class => \App\Application\Service\ProductService::class,
    \App\Domain\Repository\ProductRepositoryInterface::class  => \App\Infrastructure\Repository\ProductRepository::class,

    // user
    \App\Application\Interface\UserServiceInterface::class => \App\Application\Service\UserService::class,
    \App\Domain\Repository\UserRepositoryInterface::class  => \App\Infrastructure\Repository\UserRepository::class,
];
