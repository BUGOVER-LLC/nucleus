<?php

declare(strict_types=1);

namespace Nucleus\Contracts;

interface HasLabel
{
    /**
     * @return string|null
     */
    public function getLabel(): ?string;
}
