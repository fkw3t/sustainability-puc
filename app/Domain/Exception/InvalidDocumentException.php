<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidDocumentException extends Exception
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct(string $message)
    {
        parent::__construct($message, $this->code);
    }
}
