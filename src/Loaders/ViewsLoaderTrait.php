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
        $container_view_directory = $containerPath . '/UI/WEB/Views/';
        $container_mail_templates_directory = $containerPath . '/Mails/Templates/';

        $container_name = basename($containerPath);
        $path_parts = explode(DIRECTORY_SEPARATOR, $containerPath);
        $section_name = $path_parts[(int) count($path_parts) - 2];

        $this->loadViews($container_view_directory, $container_name, $section_name);
        $this->loadViews($container_mail_templates_directory, $container_name, $section_name);
    }

    private function loadViews(string $directory, string $container_name, ?string $section_name = null): void
    {
        if (File::isDirectory($directory)) {
            $this->loadViewsFrom($directory, $this->buildViewNamespace($section_name, $container_name));
        }
    }

    private function buildViewNamespace(?string $section_name, string $container_name): string
    {
        return $section_name
            ? (Str::camel($section_name) . '@' . Str::camel($container_name))
            : Str::camel($container_name);
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
