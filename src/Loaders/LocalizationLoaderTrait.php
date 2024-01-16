<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait LocalizationLoaderTrait
{
    /**
     * @param $containerPath
     * @return void
     */
    public function loadLocalsFromContainers($containerPath): void
    {
        $containerLocaleDirectory = $containerPath . '/Languages';
        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[count($pathParts) - 2];

        $this->loadLocals($containerLocaleDirectory, $containerName, $sectionName);
    }

    /**
     * @param $directory
     * @param $containerName
     * @param $sectionName
     * @return void
     */
    private function loadLocals($directory, $containerName, $sectionName = null): void
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
        return $sectionName ? (Str::camel($sectionName) . '@' . Str::camel($containerName)) : Str::camel(
            $containerName
        );
    }

    /**
     * @return void
     */
    public function loadLocalsFromShip(): void
    {
        $shipLocaleDirectory = config('nucleus.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Languages';
        $this->loadLocals($shipLocaleDirectory, 'ship');
    }
}
