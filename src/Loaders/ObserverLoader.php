<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Str;

trait ObserverLoader
{
    use Getters;

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
}
