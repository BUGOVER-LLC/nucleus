<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use ReflectionClass;

trait HasResourceKeyTrait
{
    /**
     * @return string
     */
    public function getResourceKey(): string
    {
        if (isset($this->resourceKey)) {
            $resourceKey = $this->resourceKey;
        } else {
            $reflect = new ReflectionClass($this);
            $resourceKey = $reflect->getShortName();
        }

        return $resourceKey;
    }
}
