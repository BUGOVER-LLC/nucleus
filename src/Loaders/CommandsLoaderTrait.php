<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Facades\Nuclear;
use Nucleus\Foundation\Nuclear as MainNuclear;
use SplFileInfo;

trait CommandsLoaderTrait
{
    /**
     * @param $containerPath
     * @return void
     */
    public function loadCommandsFromContainers(string $containerPath): void
    {
        $containerCommandsDirectory = $containerPath . '/UI/CLI/Command';
        $this->loadTheConsoles($containerCommandsDirectory);
    }

    /**
     * @param string $directory
     * @return void
     */
    private function loadTheConsoles(string $directory): void
    {
        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $consoleFile) {
                // Do not load route files
                if (!$this->isRouteFile($consoleFile)) {
                    $consoleClass = Nuclear::getClassFullNameFromFile($consoleFile->getPathname());
                    $this->commands([$consoleClass]);
                }
            }
        }
    }

    /**
     * @param SplFileInfo $consoleFile
     * @return bool
     */
    private function isRouteFile(SplFileInfo $consoleFile): bool
    {
        return 'closures.php' === $consoleFile->getFilename();
    }

    /**
     * @return void
     */
    public function loadCommandsFromShip(): void
    {
        $shipCommandsDirectory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Command';
        $this->loadTheConsoles($shipCommandsDirectory);
    }

    /**
     * @return void
     */
    public function loadCommandsFromCore(): void
    {
        $coreCommandsDirectory = __DIR__ . '/../Command';
        $this->loadTheConsoles($coreCommandsDirectory);
    }
}
