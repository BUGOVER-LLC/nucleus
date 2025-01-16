<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

trait EnvLoaderTrait
{
    /**
     * @param string $containerPath
     * @return void
     */
    public function loadEnvFromContainers(string $containerPath): void
    {
        $containerEnvDirectory = $containerPath . '/';
        $this->loadEnv($containerEnvDirectory);
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
