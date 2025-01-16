<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait ConfigsLoaderTrait
{
    /**
     * @return void
     */
    public function loadConfigsFromShip(): void
    {
        $shipConfigsDirectory = config('app.path') . MainNuclear::SHIP_NAME . '/config';
        $this->loadConfigs($shipConfigsDirectory);
    }

    /**
     * @param string $configFolder
     * @return void
     */
    private function loadConfigs(string $configFolder): void
    {
        if (File::isDirectory($configFolder)) {
            $files = File::files($configFolder);

            foreach ($files as $file) {
                $name = $file->getFilenameWithoutExtension();
                $path = $file->getPathname();

                $this->mergeConfigFrom($path, $name);
            }
        }
    }

    /**
     * @param string $containerPath
     * @return void
     */
    public function loadConfigsFromContainers(string $containerPath): void
    {
        $containerConfigsDirectory = $containerPath . DIRECTORY_SEPARATOR . 'config';
        $this->loadConfigs($containerConfigsDirectory);
    }
}
