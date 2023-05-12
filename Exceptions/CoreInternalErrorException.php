<?php

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class CoreInternalErrorException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    protected $message = 'Something went wrong!';
}
