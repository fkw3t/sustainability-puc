<?php

declare(strict_types=1);
namespace App\Application\Contracts;

interface ValidatorInterface
{
    public function validate(DTOInterface $dto): void;
    public function validateArray(array $array, array $rules): void;
}