<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class WrongConfigurationsException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'Ops! Some Containers configurations are incorrect!';
}
