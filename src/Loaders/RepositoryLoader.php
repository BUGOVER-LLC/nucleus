<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use DirectoryIterator;
use ReflectionClass;
use ReflectionException;

trait RepositoryLoader
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function loadContractRepoFromShip(): void
    {
        $_root_directory = config('app.path') . 'Ship' . DIRECTORY_SEPARATOR . 'Repositories';
        $this->loadRepositories($_root_directory);
    }

    /**
     * @param $_root_directory
     * @return void
     * @throws ReflectionException
     */
    private function loadRepositories($_root_directory): void
    {
        $dir = new DirectoryIterator($_root_directory);

        $folders = [];
        foreach ($dir as $file_info) {
            if ($file_info->isDir() && !$file_info->isDot()) {
                $folders[] = $file_info->getFilename();
            }
        }

        foreach ($folders as $folder) {
            $contract_file = str_replace('.php', '', scandir($_root_directory . $folder)[0]);
            $repo_file = str_replace('.php', '', scandir($_root_directory . $folder)[1]);

            $contract_namespace = (new ReflectionClass($contract_file))->getNamespaceName();
            $repo_namespace = (new ReflectionClass($repo_file))->getNamespaceName();

            $this->app->bindIf(
                "$contract_namespace\\$folder\\$contract_file",
                "$repo_namespace\\$folder\\$repo_file"
            );
        }
    }

    /**
     * @param $container_path
     * @return void
     * @throws ReflectionException
     */
    public function loadContractRepoFromContainers($container_path): void
    {
        $_root_directory = $container_path . '/Repositories';
        $this->loadRepositories($_root_directory);
    }
}
