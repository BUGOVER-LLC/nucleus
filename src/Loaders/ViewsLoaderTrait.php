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
        $containerViewDirectory = $containerPath . '/UI/WEB/Views/';
        $containerMailTemplatesDirectory = $containerPath . '/Mails/Templates/';

        $containerName = basename($containerPath);
        $pathParts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $sectionName = $pathParts[(int) count($pathParts) - 2];

        $this->loadViews($containerViewDirectory, $containerName, $sectionName);
        $this->loadViews($containerMailTemplatesDirectory, $containerName, $sectionName);
    }

    private function loadViews(string $directory, string $container_name, ?string $section_name = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadViewsFrom($directory, $this->buildViewNamespace($section_name, $container_name));
        }
    }

    private function buildViewNamespace(?string $sectionName, string $containerName): string
    {
        return $sectionName
            ? (Str::camel($sectionName) . '@' . Str::camel($containerName))
            : Str::camel($containerName);
    }

    /**
     * @return void
     */
    public function loadViewsFromShip(): void
    {
        $ship_mail_templates_directory = config(
            'app.path'
        ) . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Mails' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR;
        $this->loadViews($ship_mail_templates_directory, 'ship');
    }
}
