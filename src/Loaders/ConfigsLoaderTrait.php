<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;

trait ConfigsLoaderTrait
{
    /**
     * @return void
     */
    public function loadConfigsFromShip(): void
    {
        $ship_configs_directory = base_path(config('app.path') . 'Ship/Configs');
        $this->loadConfigs($ship_configs_directory);
    }

    /**
     * @param $config_folder
     * @return void
     */
    private function loadConfigs($config_folder): void
    {
        if (File::isDirectory($config_folder)) {
            $files = File::files($config_folder);

            foreach ($files as $file) {
                $name = File::name($file);
                $path = $config_folder . DIRECTORY_SEPARATOR . $name . '.php';

                $this->mergeConfigFrom($path, $name);
            }
        }
    }

    /**
     * @param $container_path
     * @return void
     */
    public function loadConfigsFromContainers($container_path): void
    {
        $container_configs_directory = $container_path . DIRECTORY_SEPARATOR . 'Configs';
        $this->loadConfigs($container_configs_directory);
    }
}
