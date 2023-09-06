<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\DTO\ExternalProductManager\Structure\ProductResponseDTO;
use App\Application\DTO\ExternalProductManager\Structure\ProductsResponseDTO;
use App\Domain\Repository\ExternalProductManagerRepositoryInterface;
use App\Infrastructure\Guzzle\GuzzleFactory;
use GuzzleHttp\Client;
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

    public function getByBarcode(string $barcode): ProductResponseDTO
    {
        $uri = sprintf('gtins/%', $barcode);
        $response = $this->client->get($uri);

        return match ($response->getStatusCode()) {
            200 => self::getProductResponseDTO(
                Utils::jsonDecode($response->getBody()->getContents(), true)
            )
        };
    }

    public function listByName(string $name): ProductsResponseDTO
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
    }

    private function getProductResponseDTO(array $response): ProductResponseDTO
    {
        return new ProductResponseDTO(
            $response['gtin'],
            $response['description'],
            $response['gpc']['description'] ?? null,
            $response['brand']['name'] ?? null,
            $response['avg_price'] ?? null,
            $response['thumbnail'] ?? null,
        );
    }

    //    public function getByBarcode(): UsageLimitResponseDTO
    //    {
    //        $uri = 'v3/rate-limits';
    //        $response = $this->client->get($uri);
    //
    //        return match ($response->getStatusCode()) {
    //            200 => self::getUsageLimitResponseDTO(
    //                Utils::jsonDecode($response->getBody()->getContents(), true)
    //            )
    //        };
    //    }
    //
    //    private function getUsageLimitResponseDTO(array $response): UsageLimitResponseDTO
    //    {
    //        return new UsageLimitResponseDTO(
    //            (int) $response['allowed_calls_per_month'],
    //            (int) $response['remaining_calls_per_month'],
    //            (int) $response['allowed_calls_per_minute'],
    //            (int) $response['remaining_calls_per_minute']
    //        );
    //    }
}
