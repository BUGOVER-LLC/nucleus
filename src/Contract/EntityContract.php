<?php

declare(strict_types=1);

namespace Nucleus\Contract;

use Service\Repository\Contracts\EloquentRepositoryContract;

interface EntityContract
{
    /**
     * @return string
     */
    public function getModelRepositoryClass(): string;

    /**
     * @return EloquentRepositoryContract
     */
    public function getModelRepositoryObject(): EloquentRepositoryContract;

    /**
     * @return void
     */
    public function flush(): void;
}
