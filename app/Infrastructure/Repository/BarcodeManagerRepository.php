<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\DTO\BarcodeManager\Request\UsageLimitResponseDTO;
use App\Domain\Repository\BarcodeManagerRepositoryInterface;
use App\Infrastructure\Http\GuzzleFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Utils;

use function Hyperf\Support\env;

class BarcodeManagerRepository implements BarcodeManagerRepositoryInterface
{
    protected Client $client;

    protected string $authKey;

    public function __construct(
        private GuzzleFactory $guzzleFactory
    ) {
        $this->client = $this->guzzleFactory->get('barcode-manager');
        $this->authKey = env('BARCODE_AUTH_KEY');
    }

    public function getUsageLimit(): UsageLimitResponseDTO
    {
        try {
            $uri = "v3/rate-limits?formatted=y&key={$this->authKey}";
            $response = $this->client->get($uri);
        } catch (ServerException $exception) {
        }

        return match ($response->getStatusCode()) {
            200 => self::getUsageLimitResponseDTO(
                Utils::jsonDecode($response->getBody()->getContents(), true)
            )
        };
    }

    private function getUsageLimitResponseDTO(array $response): UsageLimitResponseDTO
    {
        return new UsageLimitResponseDTO(
            (int) $response['allowed_calls_per_month'],
            (int) $response['remaining_calls_per_month'],
            (int) $response['allowed_calls_per_minute'],
            (int) $response['remaining_calls_per_minute']
        );
    }
}
