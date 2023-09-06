<?php

declare(strict_types=1);
namespace App\Application\Interface;

interface DTOInterface
{
    public static function getValidateRules(): array;
    public function toArray(): array;
    public static function fromArray(array $parameters): self;
}