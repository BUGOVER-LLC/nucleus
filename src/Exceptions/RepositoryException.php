<?php

declare(strict_types=1);

namespace Nucleus\Exceptions;

use Exception;

class RepositoryException extends Exception
{
    protected $code = 512;
    protected $message = 'Your request is error';
}
