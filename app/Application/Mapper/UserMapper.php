<?php

declare(strict_types=1);

namespace App\Application\Mapper;

use App\Application\DTO\User\RegisterUserRequestDTO;
use App\Domain\Entity\User;
use App\Domain\ValueObject\Document;
use DateTime;

final class UserMapper
{
    public function transformRegisterRequestDTOToEntity(RegisterUserRequestDTO $userDTO): User
    {
        return new User(
            null,
            $userDTO->name,
            new Document($userDTO->document),
            $userDTO->email,
            $userDTO->password,
            $userDTO->birthdate,
        );
    }

    public function dbModelToEntity(object $model): User
    {
        return new User(
            $model->id,
            $model->name,
            new Document($model->document_id),
            $model->email,
            $model->password,
            DateTime::createFromFormat('Y-m-d', $model->birthdate)
        );
    }
}
