<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Mapper\UserMapper;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Hyperf\DbConnection\Db as DB;
use Ramsey\Uuid\Uuid;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly UserMapper $mapper,
    ) {
    }

    public function create(User $user): bool
    {
        return DB::table('users')
            ->insert([
                'id'          => Uuid::uuid4(),
                'name'        => $user->name,
                'document_id' => $user->document->value,
                'email'       => $user->email,
                'password'    => $user->password,
                'birthdate'   => $user->birthdate->format('Y-m-d'),
            ]);
    }

    public function hasRegister(User $user): bool
    {
        $stm = DB::table('users');

        if ($user->id) {
            $stm->orWhere('id', $user->id);
        }

        if ($user->document) {
            $stm->orWhere('document_id', $user->document->value);
        }

        if ($user->email) {
            $stm->orWhere('email', $user->email);
        }

        return $stm->exists();
    }

    public function findByEmail(string $email): ?User
    {
        $model = DB::table('users')
            ->where('email', $email)
            ->first();

        return $model
            ? $this->mapper->dbModelToEntity($model)
            : null;
    }
}
