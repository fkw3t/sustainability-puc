<?php

declare(strict_types=1);
namespace App\Application\Mapper;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Domain\Entity\Product;

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
}