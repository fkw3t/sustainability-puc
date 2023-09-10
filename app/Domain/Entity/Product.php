<?php

declare(strict_types=1);

namespace App\Domain\Entity;

final class Product
{
    public function __construct(
        public string $name,
        public string $barcode,
        public ?string $brand,
        public ?string $description,
        public ?float $price,
        public ?string $image
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'barcode'     => $this->barcode,
            'brand'       => $this->brand ?? null,
            'description' => $this->description ?? null,
            'price'       => $this->price ?? null,
            'image'       => $this->image ?? null,
        ];
    }
}
