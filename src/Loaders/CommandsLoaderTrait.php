<?php

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Facades\Nuclear;

trait CommandsLoaderTrait
{
    public function loadCommandsFromContainers($containerPath): void
    {
        $containerCommandsDirectory = $containerPath . '/UI/CLI/Commands';
        $this->loadTheConsoles($containerCommandsDirectory);
    }

    private function loadTheConsoles($directory): void
    {
        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $consoleFile) {
                // Do not load route files
                if (!$this->isRouteFile($consoleFile)) {
                    $consoleClass = Nuclear::getClassFullNameFromFile($consoleFile->getPathname());
                    // When user from the Main Service Provider, which extends Laravel
                    // service provider you get access to `$this->commands`
                    $this->commands([$consoleClass]);
                }
            }
        }
    }

    private function isRouteFile($consoleFile): bool
    {
        return $consoleFile->getFilename() === 'closures.php';
    }

    public function loadCommandsFromShip(): void
    {
        $shipCommandsDirectory = base_path('app/Ship/Commands');
        $this->loadTheConsoles($shipCommandsDirectory);
    }

    public function loadCommandsFromCore(): void
    {
        $coreCommandsDirectory = __DIR__ . '/../Commands';
        $this->loadTheConsoles($coreCommandsDirectory);
    }
}
