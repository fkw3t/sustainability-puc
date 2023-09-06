<?php

declare(strict_types=1);

namespace App\Infrastructure\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
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

        $handler = HandlerStack::create();
        if ($this->config['default-query-params']) {
            $handler->push(
                new QueryStringMiddleware($this->config['default-query-params'])
            );
        }

        return ! empty($this->config)
            ? $this->clientFactory->create(
                array_merge(
                    $this->config['client'] ?? [],
                    ['handler' => $handler]
                )
            )
            : throw new \InvalidArgumentException("Invalid service. {$service} not found");
    }
}
