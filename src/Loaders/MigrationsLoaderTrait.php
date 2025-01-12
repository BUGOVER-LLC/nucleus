<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait MigrationsLoaderTrait
{
    public function loadMigrationsFromContainers(string $containerPath): void
    {
        $container_migration_directory = $containerPath . '/Data/Migrations';
        $this->loadMigrations($container_migration_directory);
    }

    /**
     * @param string $directory
     * @return void
     */
    private function loadMigrations(string $directory): void
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
        $shipMigrationDirectory = config('app.path') . MainNuclear::SHIP_NAME . DIRECTORY_SEPARATOR . 'Migrations';
        $this->loadMigrations($shipMigrationDirectory);
    }
}
