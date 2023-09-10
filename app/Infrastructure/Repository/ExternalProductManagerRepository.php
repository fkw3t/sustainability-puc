<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Application\DTO\ExternalProductManager\Structure\ProductsResponseDTO;
use App\Application\Exception\InvalidAPIKeyException;
use App\Application\Exception\ProductNotFoundException;
use App\Domain\Repository\ExternalProductManagerRepositoryInterface;
use App\Infrastructure\Guzzle\GuzzleFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ExternalProductManagerRepository implements ExternalProductManagerRepositoryInterface
{
    protected Client $client;

    private LoggerInterface $logger;

    public function __construct(
        private GuzzleFactory $guzzleFactory,
        LoggerFactory $loggerFactory,
    ) {
        $this->client = $this->guzzleFactory->get('barcode-manager');
        $this->logger = $loggerFactory->get('log', 'default');
    }

    /**
     * @param string $barcode
     * @return ProductResponseDTO
     * @throws GuzzleException
     * @throws InvalidAPIKeyException
     * @throws ProductNotFoundException
     */
    public function getByBarcode(string $barcode): ProductResponseDTO
    {
        $uri = sprintf('gtins/%s', $barcode);
        $response = $this->client->get($uri);

        return match ($response->getStatusCode()) {
            200 => self::getProductResponseDTO(
                Utils::jsonDecode($response->getBody()->getContents(), true)
            ),
            404 => throw new ProductNotFoundException(),
            501 => throw new InvalidAPIKeyException(),
        };
    }

    /**
     * @param string $name
     * @return ProductsResponseDTO
     * @throws GuzzleException
     * @throws InvalidAPIKeyException
     * @throws ProductNotFoundException
     */
    public function listByName(string $name): ProductsResponseDTO
    {
        $uri = sprintf('products?query=%s', $name);
        $response = $this->client->get($uri);

        return match ($response->getStatusCode()) {
            200 => self::getProductsResponseDTO(
                Utils::jsonDecode($response->getBody()->getContents(), true)
            ),
            404 => throw new ProductNotFoundException(),
            501 => throw new InvalidAPIKeyException(),
        };
    }

    private function getProductResponseDTO(array $response): ProductResponseDTO
    {
        return new ProductResponseDTO(
            (string) $response['gtin'],
            $response['description'],
            $response['ncm']['full_description'] ?? null,
            $response['brand']['name'] ?? null,
            isset($response['avg_price']) && $response['avg_price']
                ? round($response['avg_price'], 2)
                : null,
            $response['thumbnail'] ?? null,
        );
    }

    private function getProductsResponseDTO(array $response): ProductsResponseDTO
    {
        $products = [];

        foreach ($response['products'] as $product) {
            $products[] = self::getProductResponseDTO($product);
        }

        return new ProductsResponseDTO(
            $products,
        );
    }
}
