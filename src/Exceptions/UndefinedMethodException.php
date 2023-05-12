<?php

namespace Nucleus\Exceptions;

use Nucleus\src\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class UndefinedMethodException extends Exception
{
    protected $code = Response::HTTP_FORBIDDEN;
    protected $message = 'Undefined HTTP Verb!';
}
