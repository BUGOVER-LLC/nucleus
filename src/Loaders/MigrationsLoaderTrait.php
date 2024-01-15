<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;

trait MigrationsLoaderTrait
{
    public function loadMigrationsFromContainers($containerPath): void
    {
        $container_migration_directory = $containerPath . '/Data/Migrations';
        $this->loadMigrations($container_migration_directory);
    }

    /**
     * @param $directory
     * @return void
     */
    private function loadMigrations($directory): void
    {
        if (File::isDirectory($directory)) {
            $this->loadMigrationsFrom($directory);
        }
    }

    /**
     * @return void
     */
    public function loadMigrationsFromShip(): void
    {
        $ship_migration_directory = config('nucleus.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Migrations';
        $this->loadMigrations($ship_migration_directory);
    }
}
