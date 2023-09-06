<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\BarcodeManager\Structure\ProductResponseDTO;
use App\Application\DTO\BarcodeManager\Structure\ProductsResponseDTO;
use App\Application\Interface\BarcodeManagerServiceInterface;
use App\Domain\Repository\BarcodeManagerRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class BarcodeManagerService implements BarcodeManagerServiceInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private BarcodeManagerRepositoryInterface $barcodeManagerRepository,
        private ProductRepositoryInterface $productRepository,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function getProductByBarcode(string $barcode): ProductResponseDTO
    {
        return new ProductResponseDTO(
            '',
            '',
            null,
            null,
            null,
            null
        );
        //        try {
        //
        //        } catch (\Throwable | \Exception $exception) {
        //
        //        }
    }

    public function listProductsByName(string $name): ProductsResponseDTO
    {
        return new ProductsResponseDTO([
            new ProductResponseDTO(
                '',
                '',
                null,
                null,
                null,
                null
            ),
        ]);

        //        try {
        //
        //        } catch (\Throwable | \Exception $exception) {
        //
        //        }
    }
}
