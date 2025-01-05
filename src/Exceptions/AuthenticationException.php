<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationException extends Exception
{
    protected $code = Response::HTTP_UNAUTHORIZED;

    protected $message = 'An Exception occurred while trying to authenticate the User.';
}
