<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ServerErrorException extends Exception
{
    public function __construct(
        string $message = '',
        ?Throwable $previous = null,
        string $file = '',
        int $line = 0,
    )
    {
        if ($file) {
            $this->file = $file;
        }

        if ($line) {
            $this->line = $line;
        }

        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return jsponse(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
