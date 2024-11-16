<?php

declare(strict_types=1);

namespace Nucleus\Attributes;

use Attribute;
use Service\Repository\Contracts\EloquentRepositoryContract;

#[Attribute]
class ModelEntity
{
    public function __construct(
        public readonly string|EloquentRepositoryContract $repositoryClass = '',
        public readonly bool $readonly = true,
    )
    {
    }
}
