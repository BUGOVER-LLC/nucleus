<?php

declare(strict_types=1);

namespace Src\Loaders;

use DirectoryIterator;
use Service\Repository\Provider;

trait RepositoryLoader
{
    /**
     * @return void
     */
    public function loadContractRepo(): void
    {
        $this->app->register(Provider::class);

        $_root_directory = base_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR;
        $dir = new DirectoryIterator($_root_directory);

        $folders = [];
        foreach ($dir as $file_info) {
            if ($file_info->isDir() && !$file_info->isDot()) {
                $folders[] = $file_info->getFilename();
            }
        }

        $namespace = 'App\Repositories';

        foreach ($folders as $folder) {
            $contract_file = str_replace('.php', '', scandir($_root_directory . $folder)[2]);
            $repo_file = str_replace('.php', '', scandir($_root_directory . $folder)[3]);

            $this->loadContracts[] = $contract_file;
            $this->loadRepositories[] = $repo_file;

            $this->app->bindIf("$namespace\\$folder\\$contract_file", "$namespace\\$folder\\$repo_file");
        }
    }
}
