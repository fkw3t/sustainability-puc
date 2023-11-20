<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use App\Domain\Repository\ProductRepositoryInterface;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
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

        // TODO: fix all timestamps
    }

    public function assign(Product $product, User $user, int $quantity): bool
    {
        return DB::table('users_products')->insert([
            'id'          => Uuid::uuid4(),
            'user_id'     => $user->id,
            'product_id'  => $product->barcode,
            'expire_date' => $product->getExpireDate()->format('Y-m-d'),
            'quantity'    => $quantity,
        ]);
    }

    public function findByDate(
        User $user,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate = null
    ): array {
        return DB::table('users_products')
            ->join('products', 'users_products.product_id', '=', 'products.barcode')
            ->where('users_products.user_id', $user->id)
            ->where('users_products.expire_date', '>=', $startDate->format('Y-m-d'))
            ->where(function ($query) use ($endDate) {
                if ($endDate) {
                    $query->where('users_products.expire_date', '<=', $endDate->format('Y-m-d'));
                }
            })
            ->get([
                'products.barcode',
                'products.name',
                'products.brand',
                'products.description',
                'products.average_price',
                'products.image_url',
                'users_products.expire_date',
                'users_products.quantity',
            ])
            ->toArray();
    }

    public function findProductsCloseToExpiry(): array
    {
        $now = new DateTimeImmutable();
        $endDate = $now->add(new DateInterval('P3D'));

        return DB::table('users_products')
            ->join('products', 'users_products.product_id', '=', 'products.barcode')
            ->join('users', 'users_products.user_id', '=', 'users.id')
            ->where('users_products.expire_date', '>=', $now->format('Y-m-d'))
            ->where('users_products.expire_date', '<=', $endDate->format('Y-m-d'))
            ->get([
                'users.name as user_name',
                'users.email',
                'products.name as product_name',
                'products.image_url',
                'users_products.expire_date',
                'users_products.quantity',
            ])
            ->toArray();
    }
}
