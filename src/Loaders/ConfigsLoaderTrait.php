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
        $ship_configs_directory = config('app.path') . 'Ship/Config';
        $this->loadConfigs($ship_configs_directory);
    }

    /**
     * @param string $config_folder
     * @return void
     */
    private function loadConfigs(string $config_folder): void
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
     * @param string $container_path
     * @return void
     */
    public function loadConfigsFromContainers(string $container_path): void
    {
        $container_configs_directory = $container_path . DIRECTORY_SEPARATOR . 'Config';
        $this->loadConfigs($container_configs_directory);
    }
}
