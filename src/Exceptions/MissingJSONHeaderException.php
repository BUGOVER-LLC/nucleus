<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response;

class MissingJSONHeaderException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'Your request must contain [Accept = application/json].';
}
