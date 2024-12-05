<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Macro;

abstract class Macro
{
    abstract protected function register(): void;

    final public static function bind(): void
    {
        app(static::class)->register();
    }
}
