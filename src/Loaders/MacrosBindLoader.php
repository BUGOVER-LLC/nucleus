<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Contract\MacrosContract;
use Nucleus\Foundation\Facades\Nuclear;

trait MacrosBindLoader
{
    private function loadMacrosFromShip()
    {
        $shipMacrosDirectory = config('nucleus.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Macros';
        $this->loadTheMacros($shipMacrosDirectory);
    }

    private function loadMacrosFromContainers(string $container_path)
    {
        $shipMacrosDirectory = $container_path . DIRECTORY_SEPARATOR . 'Macros';
        $this->loadTheMacros($shipMacrosDirectory);
    }

    /**
     * @param string $directory
     * @return void
     */
    private function loadTheMacros(string $directory): void
    {
        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $macrosFile) {
                // Do not load route files
                if ($macrosFile) {
                    $consoleClass = Nuclear::getClassFullNameFromFile($macrosFile->getPathname());
                    if ((new $consoleClass()) instanceof MacrosContract) {
                        $consoleClass::bind();
                    }
                }
            }
        }
    }
}
