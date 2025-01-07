<?php

declare(strict_types=1);

namespace Nucleus\Contract;

interface HasResourceKey
{
    public function getResourceKey(): string;
}
