<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use App\Domain\Repository\ProductRepositoryInterface;
use Hyperf\DbConnection\Db as DB;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ProductRepository implements ProductRepositoryInterface
{
    private LoggerInterface $logger;

    public function __construct(
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function findByBarcode(string $barcode): ?Product
    {
        $product = DB::table('products')
            ->where('barcode', $barcode)
            ->first();

        if (! $product) {
            return null;
        }

        return new Product(
            $product->name,
            $product->barcode,
            $product->brand ?? null,
            $product->description ?? null,
            (float) $product->average_price ?? null,
            $product->image_url ?? null,
        );
    }

    public function create(Product $product): bool
    {
        return DB::table('products')->insert([
            'barcode'       => $product->barcode,
            'name'          => $product->name,
            'brand'         => $product->brand,
            'description'   => $product->description,
            'average_price' => $product->price,
            'image_url'     => $product->image,
        ]);
    }

    public function assign(Product $product, User $user, int $quantity): bool
    {
        return DB::table('users_products')->insert([
            'id'          => Uuid::uuid4(),
            'user_id'     => $user->id,
            'product_id'  => $product->barcode,
            'expire_date' => $product->getExpireDate()->format('Y-m-d'),
            'quantity'    => $quantity
        ]);
    }
}
