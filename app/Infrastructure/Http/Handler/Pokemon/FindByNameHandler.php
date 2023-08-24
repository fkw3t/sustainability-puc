<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Handler\Pokemon;

use App\Domain\Repository\PokemonRepositoryInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class FindByNameHandler
{
    public function __construct(
        private PokemonRepositoryInterface $pokemonRepository
    ) {
    }

    public function handle(RequestInterface $request, ResponseInterface $response): array
    {
        return $this->pokemonRepository->findByName($request->input('name'));
    }
}
