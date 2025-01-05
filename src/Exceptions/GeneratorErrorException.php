<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class GeneratorErrorException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'Generator Error.';
}
