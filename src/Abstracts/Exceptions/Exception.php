<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Exceptions;

use Exception as BaseException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Exception extends BaseException
{
    /**
     * @var string|mixed
     */
    protected string $environment;
    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @link https://php.net/manual/en/exception.construct.php
     * @param string|null $message [optional] The Exception message to throw.
     * @param int|null $code [optional] The Exception code.
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        Throwable $previous = null
    )
    {
        // Detect and set the running environment
        $this->environment = Config::get('app.env');

        parent::__construct($this->prepareMessage($message), $this->prepareStatusCode($code), $previous);
    }

    /**
     * @param string|null $message
     * @return string
     */
    private function prepareMessage(?string $message = null): string
    {
        return $message ?? $this->message;
    }

    /**
     * @param int|null $code
     * @return int
     */
    private function prepareStatusCode(?int $code = null): int
    {
        return $code ?? $this->code;
    }

    /**
     * Help developers debug the error without showing these details to the end user.
     * Usage: `throw (new MyCustomException())->debug($e)`.
     *
     * @param $error
     * @param bool $force
     *
     * @return $this
     */
    public function debug($error, bool $force = false): Exception
    {
        if ($error instanceof BaseException) {
            $error = $error->getMessage();
        }

        if ('testing' !== $this->environment || $force === true) {
            Log::error('[DEBUG] ' . $error);
        }

        return $this;
    }

    /**
     * @param array $errors
     * @param bool $override
     * @return $this
     */
    public function withErrors(array $errors, bool $override = true): Exception
    {
        if ($override) {
            $this->errors = $errors;
        } else {
            $this->errors = array_merge($this->errors, $errors);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $translatedErrors = [];

        foreach ($this->errors as $key => $value) {
            $translatedValues = [];
            // here we translate and mutate each error so all error values will be arrays (for consistency)
            // e.g. error => value becomes error => [translated_value]
            // e.g. error => [value1, value2] becomes error => [translated_value1, translated_value2]
            if (\is_array($value)) {
                foreach ($value as $translationKey) {
                    $translatedValues[] = __($translationKey);
                }
            } else {
                $translatedValues[] = __($value);
            }

            $translatedErrors[$key] = $translatedValues;
        }

        return $translatedErrors;
    }
}
