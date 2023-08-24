<?php

namespace App\Domain\Repository;

interface PokemonRepositoryInterface
{
    public function findByName(string $name): array;
}