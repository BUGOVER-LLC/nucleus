<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Nucleus\Contract\EntityContract;

trait ModelMapLoader
{
    /**
     * @return string[]
     */
    private function loadModelMapsFromContainers(string $container_path): array
    {
        $container_models = array_keys(
            ClassMapGenerator::createMap($container_path . DIRECTORY_SEPARATOR . 'Models')
        );

        $this->load($container_models);
    }

    /**
     * @param array $models
     * @return array
     */
    private function load(array $models): array
    {
        $result = [];
        foreach ($models as $model) {
            if (class_exists($model)) {
                $instance = (new $model());
                if ($instance instanceof EntityContract && property_exists($model, 'map') && $instance->getMap()) {
                    $result[$instance->getMap()] = $model;
                }
            }
        }

        Relation::morphMap($result);

        return $result;
    }

    /**
     * @return void
     */
    private function loadModelsMapFormShip(): void
    {
        $ship_models_directory = base_path(
            config('nucleus.path') . 'Ship/Models'
        );
        $models = array_keys(ClassMapGenerator::createMap($ship_models_directory));

        $this->load($models);
    }
}
