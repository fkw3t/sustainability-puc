<?php

declare(strict_types=1);

namespace App\Application\Interface;

interface ValidatorInterface
{
    public function validate(DTOInterface $dto): void;

    public function validateArray(array $array, array $rules): void;
}
