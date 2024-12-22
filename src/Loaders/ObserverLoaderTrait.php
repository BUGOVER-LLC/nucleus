<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nucleus\Contract\EntityContract;

trait ObserverLoaderTrait
{
    public function loadObserverFromContainers(string $container_path)
    {
        $container_models_directory = $container_path . DIRECTORY_SEPARATOR . 'Models';
        $container_observers_directory = $container_path . DIRECTORY_SEPARATOR . 'Observer';

        $this->loadObserve($container_models_directory, $container_observers_directory);
    }

    /**
     * @param string $modelDirectory
     * @param string $observerDirectory
     * @return void
     */
    private function loadObserve(string $modelDirectory, string $observerDirectory): void
    {
        if (File::isDirectory($modelDirectory)) {
            $modelFiles = File::allFiles($modelDirectory);
            $observerFiles = File::allFiles($observerDirectory);

            /* @var EntityContract $modelFile */
            foreach ($modelFiles as $modelFile) {
                foreach ($observerFiles as $observerFile) {
                    if ((Str::afterLast($modelFile, '\\') . 'Observe') === (Str::afterLast($observerFile, '\\'))) {
                        $modelFile::observe($observerFile);
                    }
                }
            }
        }
    }

    /**
     * @return void
     */
    private function loadObserversMapFormShip(): void
    {
        $ship_models_directory = base_path(config('nucleus.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Observer');
        $this->loadModels($ship_models_directory);
    }
}
