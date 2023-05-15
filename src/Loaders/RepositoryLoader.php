<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use DirectoryIterator;

trait RepositoryLoader
{
    /**
     * @return void
     */
    public function loadContractRepo(): void
    {
        $_root_directory = config(
                'app.path'
            ) . 'NoixContainers' . DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR;
        $dir = new DirectoryIterator($_root_directory);

        $folders = [];
        foreach ($dir as $file_info) {
            if ($file_info->isDir() && !$file_info->isDot()) {
                $folders[] = $file_info->getFilename();
            }
        }

        $namespace = 'NoixContainers\Vendor';

        foreach ($folders as $folder) {
            $contract_file = str_replace('.php', '', scandir($_root_directory . $folder)[2]);
            $repo_file = str_replace('.php', '', scandir($_root_directory . $folder)[3]);

            $this->loadContracts[] = $contract_file;
            $this->loadRepositories[] = $repo_file;

            $this->app->bindIf("$namespace\\$folder\\$contract_file", "$namespace\\$folder\\$repo_file");
        }
    }
}
