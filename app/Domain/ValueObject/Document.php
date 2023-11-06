<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Trait\DocumentValidationTrait;

readonly class Document
{
    use DocumentValidationTrait;

    public string $value;

    public function __construct(
        string $value,
    ) {
        $this->value = $this->validate($value);
    }
}
