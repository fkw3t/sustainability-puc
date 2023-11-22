<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\Product\Request\AssignProductRequestDTO;
use App\Application\DTO\Product\Structure\ExpiringProductNotificationDTO;
use App\Application\DTO\Product\Structure\ExpiringProductsNotificationDTO;
use App\Application\DTO\Product\Structure\ProductResponseDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductsFromExpireDateResponseDTO;
use App\Application\Exception\Product\ProductNotFoundException;
use App\Application\Exception\UserNotFoundException;
use App\Application\Interface\ExternalProductManagerServiceInterface;
use App\Application\Interface\ProductServiceInterface;
use App\Application\Interface\UserServiceInterface;
use App\Application\Mapper\ProductMapper;
use App\Domain\Exception\InvalidExpireDateException;
use App\Domain\Repository\ProductRepositoryInterface;
use DateInterval;
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
     * @param AssignProductRequestDTO $assignDTO
     * @return bool
     * @throws InvalidExpireDateException
     */
    public function assign(AssignProductRequestDTO $assignDTO): bool
    {
        /** @throws UserNotFoundException */
        $user = $this->userService->findUser($assignDTO->userId);

        if (! $product = $this->repository->findByBarcode($assignDTO->productResponseDTO->barcode)) {
            $this->repository->create(
                $this->productMapper->transformResponseDTOToEntity($assignDTO->productResponseDTO)
            );

            $product = $this->repository->findByBarcode($assignDTO->productResponseDTO->barcode);
        }

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
                    $now->add(new DateInterval('P14D'))
                )
            ),
        );
    }

    public function todayExpiringProducts(): ExpiringProductsNotificationDTO
    {
        $data = $this->repository->findProductsCloseToExpiry();
        $expiringProducts = [];
        foreach ($data as $expiringProduct) {
            $expiringProduct = (array) $expiringProduct;
            $now = new DateTimeImmutable();
            $expireDate = DateTimeImmutable::createFromFormat('Y-m-d', $expiringProduct['expire_date']);

            $expiringProducts[] = new ExpiringProductNotificationDTO(
                $expiringProduct['user_name'],
                $expiringProduct['email'],
                $expiringProduct['product_name'],
                $expiringProduct['image_url'],
                $expiringProduct['expire_date'],
                $expiringProduct['quantity'],
                $now->diff($expireDate)->days,
            );
        }

        return new ExpiringProductsNotificationDTO($expiringProducts);
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
