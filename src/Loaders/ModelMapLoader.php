<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\File;
use Nucleus\Contract\EntityContract;
use Nucleus\Foundation\Facades\Nuclear;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait ModelMapLoader
{
    /**
     * @param string $container_path
     * @return void
     */
    private function loadModelMapsFromContainers(string $container_path): void
    {
        $container_models_directory = $container_path . DIRECTORY_SEPARATOR . 'Model';
        $this->loadModels($container_models_directory);
    }

    /**
     * @param string $directory
     * @return void
     */
    private function loadModels(string $directory): void
    {
        $result = [];

        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $modelFile) {
                $modelClass = Nuclear::getClassFullNameFromFile($modelFile->getPathname());
                $instance = (new $modelClass());
                if ($instance instanceof EntityContract && property_exists($instance, 'map') && $instance->getMap()) {
                    $result[$instance->getMap()] = $modelFile;
                }
            }
        }

        Relation::morphMap($result);
    }

    /**
     * @return void
     */
    private function loadModelsMapFormShip(): void
    {
        $ship_models_directory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Model';
        $this->loadModels($ship_models_directory);
    }
}
