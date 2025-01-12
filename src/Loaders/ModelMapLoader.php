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
    private static array $loadedModels = [];

    /**
     * @param string $containerPath
     * @return void
     */
    private function loadModelMapsFromContainers(string $containerPath): void
    {
        if (!empty(self::$loadedModels)) {
            return;
        }

        $containerModelsDirectory = $containerPath . DIRECTORY_SEPARATOR . 'Model';
        $this->loadModels($containerModelsDirectory);
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
                if ($instance instanceof EntityContract && property_exists($instance, 'map')) {
                    self::$loadedModels[$instance->getMap()] = $modelFile;
                }
            }
        }

        Relation::morphMap(self::$loadedModels);
    }

    /**
     * @return void
     */
    private function loadModelsMapFormShip(): void
    {
        $shipModelsDirectory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Model';
        $this->loadModels($shipModelsDirectory);
    }
}
