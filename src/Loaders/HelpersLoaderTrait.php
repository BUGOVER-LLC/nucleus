<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait HelpersLoaderTrait
{
    public function loadHelpersFromContainers(string $containerPath): void
    {
        $containerHelpersDirectory = $containerPath . '/helpers';
        $this->loadHelpers($containerHelpersDirectory);
    }

    private function loadHelpers(string $helpersFolder): void
    {
        if (File::isDirectory($helpersFolder)) {
            $files = File::files($helpersFolder);

            foreach ($files as $file) {
                try {
                    require($file);
                } catch (FileNotFoundException $e) {
                }
            }
        }
    }

    public function loadHelpersFromShip(): void
    {
        $shipHelpersDirectory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'helpers';
        $this->loadHelpers($shipHelpersDirectory);
    }
}
