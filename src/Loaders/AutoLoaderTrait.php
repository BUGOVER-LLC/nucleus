<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Nucleus\Foundation\Facades\Nuclear;

trait AutoLoaderTrait
{
    // Using each component loader trait
    use ConfigsLoaderTrait;
    use LocalizationLoaderTrait;
    use MigrationsLoaderTrait;
    use ViewsLoaderTrait;
    use ProvidersLoaderTrait;
    use CommandsLoaderTrait;
    use AliasesLoaderTrait;
    use HelpersLoaderTrait;
    use RepositoryLoader;
    use ModelMapLoader;
    use ObserverLoader;

    /**
     * To be used from the `boot` function of the main service provider
     */
    public function runLoadersBoot(): void
    {
        $this->loadMigrationsFromShip();
        $this->loadLocalsFromShip();
        $this->loadViewsFromShip();
        $this->loadHelpersFromShip();
        $this->loadCommandsFromShip();
        $this->loadCommandsFromCore();
        $this->loadContractRepo();
//        $this->loadMaps();
//        $this->loadObservers();

        // Iterate over all the containers folders and autoload most of the components
        foreach (Nuclear::getAllContainerPaths() as $container_path) {
            $this->loadMigrationsFromContainers($container_path);
            $this->loadLocalsFromContainers($container_path);
            $this->loadViewsFromContainers($container_path);
            $this->loadHelpersFromContainers($container_path);
            $this->loadCommandsFromContainers($container_path);
        }
    }

    /**
     * @return void
     */
    public function runLoaderRegister(): void
    {
        $this->loadConfigsFromShip();
        $this->loadOnlyShipProviderFromShip();

        foreach (Nuclear::getAllContainerPaths() as $container_path) {
            $this->loadConfigsFromContainers($container_path);
            $this->loadOnlyMainProvidersFromContainers($container_path);
        }
    }
}
