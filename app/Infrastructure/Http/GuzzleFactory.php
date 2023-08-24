<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Guzzle\ClientFactory;

class GuzzleFactory
{
    protected ?array $config = null;

    public function __construct(
        protected ContainerInterface $container,
        private ClientFactory $clientFactory,
    ) {
    }

    public function get(string $service): Client
    {
        $this->config = $this->container
            ->get(ConfigInterface::class)
            ->get("guzzle.{$service}");

        return ! empty($this->config)
            ? $this->clientFactory->create($this->config['client'])
            : throw new \InvalidArgumentException("Invalid service. {$service} not found");
    }
}
