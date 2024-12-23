<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Facades\Nuclear;

trait CommandsLoaderTrait
{
    /**
     * @param $containerPath
     * @return void
     */
    public function loadCommandsFromContainers($containerPath): void
    {
        $containerCommandsDirectory = $containerPath . '/UI/CLI/Commands';
        $this->loadTheConsoles($containerCommandsDirectory);
    }

    /**
     * @param $directory
     * @return void
     */
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

    /**
     * @param $consoleFile
     * @return bool
     */
    private function isRouteFile($consoleFile): bool
    {
        return 'closures.php' === $consoleFile->getFilename();
    }

    /**
     * @return void
     */
    public function loadCommandsFromShip(): void
    {
        $shipCommandsDirectory = config('app.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Commands';
        $this->loadTheConsoles($shipCommandsDirectory);
    }

    /**
     * @return void
     */
    public function loadCommandsFromCore(): void
    {
        $coreCommandsDirectory = __DIR__ . '/../Commands';
        $this->loadTheConsoles($coreCommandsDirectory);
    }
}
