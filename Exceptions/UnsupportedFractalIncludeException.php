<?php

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class UnsupportedFractalIncludeException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'Requested a invalid Include Parameter.';
}
