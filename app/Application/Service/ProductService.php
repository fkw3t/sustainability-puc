<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Application\Interface\ProductServiceInterface;
use App\Application\Mapper\ProductMapper;
use App\Domain\Repository\ProductRepositoryInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ProductService implements ProductServiceInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ExternalProductManagerServiceInterface $externalProductService,
        private readonly ProductRepositoryInterface $repository,
        private readonly ProductMapper $mapper,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @param string $barcode
     * @return ProductResponseDTO
     */
    public function getProductByBarcode(string $barcode): ProductResponseDTO
    {
        if ($product = $this->repository->findByBarcode($barcode)) {
            return $this->mapper->transformEntityToResponseDTO($product);
        }

        $product = $this->externalProductService->getProductByBarcode($barcode);
        $this->create($product);

        return $product;
    }

    /**
     * @param ProductResponseDTO $product
     * @return bool
     */
    public function create(ProductResponseDTO $product): bool
    {
        return $this->repository->create(
            $this->mapper->transformResponseDTOToEntity($product)
        );
    }
}
