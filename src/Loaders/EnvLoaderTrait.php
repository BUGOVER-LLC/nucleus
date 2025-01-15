<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

trait EnvLoaderTrait
{
    /**
     * @param string $container_path
     * @return void
     */
    public function loadEnvFromContainers(string $container_path): void
    {
        $container_env_directory = $container_path . '/';
        $this->loadEnv($container_env_directory);
    }

    /**
     * @param string $directory
     * @return void
     * @readonly true
     */
    private function loadEnv(string $directory): void
    {
        if (File::isDirectory($directory)) {
            $env_file = $directory . '.env';

            if (file_exists($env_file)) {
                $dotenv = Dotenv::createImmutable(
                    [
                        $directory,
                    ],
                    [
                        '.env',
                    ]
                );
                $dotenv->safeLoad();
            }
        }
    }
}
