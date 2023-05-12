<?php

declare(strict_types=1);

namespace Src\Loaders;

trait Getters
{
    /**
     * @var array
     */
    private array $models = [];

    /**
     * @var array
     */
    private array $observers = [];

    /**
     * @return array
     */
    public function getObservers(): array
    {
        if (!$this->observers) {
            $path = app_path('Observers' . DIRECTORY_SEPARATOR);
            recursive_loader($path, $this->observers, true);
        }

        return $this->observers;
    }//end getObservers()

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
    }//end getModels()
}
