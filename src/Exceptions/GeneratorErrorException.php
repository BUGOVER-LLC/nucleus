<?php

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class GeneratorErrorException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'Generator Error.';
}
