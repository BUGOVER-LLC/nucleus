<?php

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\File;

trait MigrationsLoaderTrait
{
    public function loadMigrationsFromContainers($containerPath): void
    {
        $containerMigrationDirectory = $containerPath . '/Data/Migrations';
        $this->loadMigrations($containerMigrationDirectory);
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
        $shipMigrationDirectory = base_path('app/Ship/Migrations');
        $this->loadMigrations($shipMigrationDirectory);
    }
}
