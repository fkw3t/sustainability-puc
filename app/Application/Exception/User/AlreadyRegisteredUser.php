<?php

declare(strict_types=1);

namespace App\Application\Exception\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AlreadyRegisteredUser extends Exception
{
    protected $code = Response::HTTP_CONFLICT;

    public function __construct()
    {
        parent::__construct('Already registered user', $this->code);
    }
}
