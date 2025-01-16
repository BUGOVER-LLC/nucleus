<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait LocalizationLoaderTrait
{
    /**
     * @param string $containerPath
     * @return void
     */
    public function loadLocalsFromContainers(string $containerPath): void
    {
        $containerLocaleDirectory = $containerPath . '/languages';
        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[(int) count($pathParts) - 2];

        $this->loadLocals($containerLocaleDirectory, $containerName, $sectionName);
    }

    /**
     * @param string $directory
     * @param string $containerName
     * @param string|null $sectionName
     * @return void
     */
    private function loadLocals(string $directory, string $containerName, ?string $sectionName = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadTranslationsFrom($directory, $this->buildLocaleNamespace($sectionName, $containerName));
            $this->loadJsonTranslationsFrom($directory);
        }
    }

    /**
     * @param string|null $sectionName
     * @param string $containerName
     * @return string
     */
    private function buildLocaleNamespace(?string $sectionName, string $containerName): string
    {
        return $sectionName
            ? (Str::snake($sectionName) . '@' . Str::snake($containerName))
            : Str::snake($containerName);
    }

    /**
     * @return void
     */
    public function loadLocalsFromShip(): void
    {
        $shipLocaleDirectory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'languages';
        $this->loadLocals($shipLocaleDirectory, 'ship');
    }
}
