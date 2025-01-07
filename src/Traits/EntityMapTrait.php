<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use JetBrains\PhpStorm\Pure;

trait EntityMapTrait
{
    /**
     * @return string
     */
    public static function map(): string
    {
        return (new static())->map;
    }

    /**
     * @return string
     */
    #[Pure] public function getMap(): string
    {
        return (new static())->map;
    }
}
