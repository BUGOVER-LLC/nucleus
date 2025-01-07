<?php

declare(strict_types=1);

namespace Nucleus\Contract;

use JetBrains\PhpStorm\Pure;

interface EntityMapContract
{
    /**
     * @return string
     */
    public static function map(): string;

    /**
     * @return string
     */
    public function getMap(): string;
}
