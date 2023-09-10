<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidAPIKeyException extends Exception
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    public function __construct()
    {
        parent::__construct('Invalid API key', $this->code);
    }
}
