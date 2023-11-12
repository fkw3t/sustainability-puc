<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Exception\InvalidExpireDateException;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

final class Product
{
    /**
     * @throws InvalidExpireDateException
     */
    public function __construct(
        public string             $name,
        public string             $barcode,
        public ?string            $brand,
        public ?string            $description,
        public ?float             $price,
        public ?string            $image,
        private ?DateTimeInterface $expireDate = null
    ) {
        $this->setExpireDate($expireDate);
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
            'expire_date' => $this->expireDate ?? null
        ];
    }

    public function setExpireDate(?DateTimeInterface $expireDate = null): ?DateTimeInterface
    {
        if(! $expireDate) {
            return null;
        }

        $now = new DateTime();

        if ($now->format('Y-m-d') >= $expireDate->format('Y-m-d')) {
            throw new InvalidExpireDateException('Invalid expire date. Use future date.');
        }

        return $this->expireDate = $expireDate;
    }

    public function getExpireDate(): ?DateTimeInterface
    {
        return $this->expireDate;
    }
}
