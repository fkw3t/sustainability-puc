<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\Product\Request\AssignProductRequestDTO;
use App\Application\DTO\Product\Structure\ProductResponseDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductsFromExpireDateResponseDTO;
use App\Application\Exception\Product\ProductNotFoundException;
use App\Application\Exception\UserNotFoundException;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Application\Interface\ProductServiceInterface;
use App\Application\Interface\UserServiceInterface;
use App\Application\Mapper\ProductMapper;
use App\Domain\Repository\ProductRepositoryInterface;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ProductService implements ProductServiceInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ProductRepositoryInterface $repository,
        private readonly ProductMapper $productMapper,
        private readonly ExternalProductManagerServiceInterface $externalProductService,
        private readonly UserServiceInterface $userService,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function getProductByBarcode(string $barcode): ProductResponseDTO
    {
        if ($product = $this->repository->findByBarcode($barcode)) {
            return $this->productMapper->transformEntityToResponseDTO($product);
        }

        $product = $this->externalProductService->getProductByBarcode($barcode);
        $this->create($product);

        return $product;
    }

    public function create(ProductResponseDTO $product): bool
    {
        return $this->repository->create(
            $this->productMapper->transformResponseDTOToEntity($product)
        );
    }

    /**
     * @throws ProductNotFoundException
     */
    public function assign(AssignProductRequestDTO $assignDTO): bool
    {
        /** @throws UserNotFoundException */
        $user = $this->userService->findUser($assignDTO->userId);

        if(! $product = $this->repository->findByBarcode($assignDTO->productResponseDTO->barcode)) {
            throw new ProductNotFoundException();
        };

        $product->setExpireDate($assignDTO->expireDate);

        return $this->repository->assign($product, $user, $assignDTO->quantity);
    }

    public function listUserRegisteredProducts(string $userId): UserRegisteredProductsFromExpireDateResponseDTO
    {
        /** @throws UserNotFoundException */
        $user = $this->userService->findUser($userId);
        $now = new DateTimeImmutable();

        return new UserRegisteredProductsFromExpireDateResponseDTO(
            $this->formatRegisteredProductsList(
                $this->repository->findByDate(
                    $user,
                    $now,
                    $now->add(new DateInterval('P7D'))
                )
            ),
            $this->formatRegisteredProductsList(
                $this->repository->findByDate(
                    $user,
                    $now->add(new DateInterval('P7D')),
                    $now->add(new DateInterval('P14D'))
                )
            ),
            $this->formatRegisteredProductsList(
                $this->repository->findByDate(
                    $user,
                    $now->sub(new DateInterval('P14D'))
                )
            ),
        );
    }

    /** @return UserRegisteredProductDTO[] */
    private function formatRegisteredProductsList(array $products): array
    {
        $productsDTO = [];

        foreach ($products as $product) {
            $productsDTO[] = $this->productMapper->transformArrayRegisteredProductToDTO((array) $product);
        }

        return $productsDTO;
    }
}
