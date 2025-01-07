<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Nucleus\Attributes\ModelEntity;
use ReflectionClass;
use Service\Repository\Contracts\EloquentRepositoryContract;

trait EntityRepositoryTrait
{

    public function flush(): void
    {
        $this->getModelRepositoryObject()->forgetCache();
    }

    public function getModelRepositoryObject(): EloquentRepositoryContract
    {
        return app($this->getModelRepositoryClass());
    }

    /**
     * @return string
     */
    public function getModelRepositoryClass(): string
    {
        $reflectionClass = new ReflectionClass(static::class);

        return collect($reflectionClass->getAttributes(ModelEntity::class))
            ->map(fn($attribute) => $attribute->getArguments())
            ->flatten()
            ->first();
    }
}
