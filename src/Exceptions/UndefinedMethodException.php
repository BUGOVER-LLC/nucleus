<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class UndefinedMethodException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;

    protected $message = 'Undefined HTTP Verb!';
}
