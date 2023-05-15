<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Str;

trait ObserverLoader
{
    /**
     * @var array
     */
    private array $observers = [];

    /**
     * @return void
     */
    protected function loadObservers(): void
    {
        foreach ($this->getModels() as $model) {
            foreach ($this->getObservers() as $observer) {
                if ((Str::afterLast($model, '\\') . 'Observer') === (Str::afterLast($observer, '\\'))) {
                    $model::observe($observer);
                }
            }
        }
    }

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
    }
}
