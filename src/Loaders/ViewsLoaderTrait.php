<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait ViewsLoaderTrait
{
    public function loadViewsFromContainers(string $containerPath): void
    {
        $containerViewDirectory = $containerPath . '/UI/WEB/views/';
        $containerMailTemplatesDirectory = $containerPath . '/Mails/templates/';

        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[(int) count($pathParts) - 2];

        $this->loadViews($containerViewDirectory, $containerName, $sectionName);
        $this->loadViews($containerMailTemplatesDirectory, $containerName, $sectionName);
    }

    private function loadViews(string $directory, string $containerName, ?string $sectionName = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadViewsFrom($directory, $this->buildViewNamespace($sectionName, $containerName));
        }
    }

    private function buildViewNamespace(?string $sectionName, string $containerName): string
    {
        return $sectionName
            ? (Str::snake($sectionName, '-') . '@' . Str::snake($containerName, '-'))
            : Str::snake($containerName, '-');
    }

    /**
     * @return void
     */
    public function loadViewsFromShip(): void
    {
        $shipMailTemplatesDirectory = config(
            'app.path'
        ) . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Mails' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $this->loadViews($shipMailTemplatesDirectory, 'ship');
    }
}
