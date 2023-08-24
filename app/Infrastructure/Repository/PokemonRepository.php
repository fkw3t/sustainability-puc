<?php

namespace App\Infrastructure\Repository;
use App\Domain\Repository\PokemonRepositoryInterface;
use App\Infrastructure\Http\GuzzleFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;

class PokemonRepository implements PokemonRepositoryInterface
{
    protected Client $client;

    public function __construct(
        private GuzzleFactory $guzzleFactory
    ) {
        $this->client = $this->guzzleFactory->get('pokemon');
    }

    public function findByName(string $name): array
    {
        $uri = "pokemon/{$name}";
        $response = $this->client->get($uri);

        return Utils::jsonDecode($response->getBody()->getContents(), true);
    }
}
