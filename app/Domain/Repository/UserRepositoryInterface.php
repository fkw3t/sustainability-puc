<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function create(User $user): bool;
    public function hasRegister(User $user): bool;
    public function findByEmail(string $email): ?User;
    public function find(string $id): ?User;
}
