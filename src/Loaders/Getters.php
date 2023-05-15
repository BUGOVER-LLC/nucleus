<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

trait Getters
{
    /**
     * @var array
     */
    private array $models = [];

    /**
     * @return array
     */
    private function getModels(): array
    {
        if (!$this->models) {
            $path = base_path('src') . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR;
            recursive_loader($path, $this->models, true);
        }

        return $this->models;
    }
}
