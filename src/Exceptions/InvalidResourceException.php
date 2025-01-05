<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Nucleus\Abstracts\Exception\Exception;
use Nucleus\Abstracts\Resource\Resource;
use Symfony\Component\HttpFoundation\Response;

class InvalidResourceException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    protected $message = 'Resource must extended the ' . Resource::class . ' class.';
}
