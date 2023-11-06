<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends Exception
{
    protected $code = Response::HTTP_NOT_FOUND;

    public function __construct()
    {
        parent::__construct('We cannot find user with this email', $this->code);
    }
}
