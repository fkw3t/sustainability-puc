<?php

declare(strict_types=1);

namespace App\Application\Mapper;

use App\Application\DTO\Product\Structure\ProductResponseDTO;
use App\Application\DTO\Product\Structure\UserRegisteredProductDTO;
use App\Domain\Entity\Product;
use DateTime;
use DateTimeImmutable;

final class ProductMapper
{
    public function transformResponseDTOToEntity(ProductResponseDTO $productDTO): Product
    {
        return new Product(
            $productDTO->name,
            $productDTO->barcode,
            $productDTO->brand ?? null,
            $productDTO->description ?? null,
            $productDTO->averagePrice ?? null,
            $productDTO->imageUrl ?? null
        );
    }

    public function transformEntityToResponseDTO(Product $product): ProductResponseDTO
    {
        return new ProductResponseDTO(
            $product->barcode,
            $product->name,
            $product->description ?? null,
            $product->brand ?? null,
            $product->price ?? null,
            $product->image ?? null,
        );
    }

    public function transformArrayRegisteredProductToDTO(array $productList): UserRegisteredProductDTO
    {
        $now = new DateTimeImmutable();
        $expireDate = DateTimeImmutable::createFromFormat('Y-m-d', $productList['expire_date']);

        return new UserRegisteredProductDTO(
            $productList['barcode'],
            $productList['name'],
            $productList['description'] ?? null,
            $productList['brand'] ?? null,
            (float) $productList['average_price'] ?? null,
            $productList['image_url'] ?? null,
            $expireDate->format('Y-m-d'),
            $productList['quantity'],
            $now->diff($expireDate)->days,
        );
    }
}
