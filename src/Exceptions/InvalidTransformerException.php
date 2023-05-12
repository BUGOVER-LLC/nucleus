<?php

namespace Nucleus\Exceptions;

use Nucleus\src\Abstracts\Exceptions\Exception;
use Nucleus\src\Abstracts\Transformers\Transformer;
use Symfony\Component\HttpFoundation\Response;

class InvalidTransformerException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    protected $message = 'Transformers must extended the ' . Transformer::class . ' class.';
}
