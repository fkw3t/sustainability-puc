<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Application\DTO\ExternalProductManager\Structure\ProductsResponseDTO;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Domain\Repository\ExternalProductManagerRepositoryInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ExternalProductManagerService implements ExternalProductManagerServiceInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ExternalProductManagerRepositoryInterface $repository,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function getProductByBarcode(string $barcode): ProductResponseDTO
    {
        return $this->repository->getByBarcode($barcode);
    }

    public function listProductsByName(string $name): ProductsResponseDTO
    {
        return $this->repository->listByName($name);
    }
}
