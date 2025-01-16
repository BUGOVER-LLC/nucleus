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
    use ModelMapLoader;
    use EnvLoaderTrait;

    /**
     * To be used from the `boot` function of the main service provider.
     */
    public function runLoadersBoot(): void
    {
        // Ship folder and autoload most of the components.
        $this->loadMigrationsFromShip();
        $this->loadLocalsFromShip();
        $this->loadViewsFromShip();
        $this->loadHelpersFromShip();
        $this->loadCommandsFromShip();
        $this->loadCommandsFromCore();
        $this->loadModelsMapFormShip();

        // Iterate over all the containers folders and autoload most of the components.
        foreach (Nuclear::getAllContainerPaths() as $containerPath) {
            $this->loadMigrationsFromContainers($containerPath);
            $this->loadLocalsFromContainers($containerPath);
            $this->loadViewsFromContainers($containerPath);
            $this->loadHelpersFromContainers($containerPath);
            $this->loadCommandsFromContainers($containerPath);
            $this->loadModelMapsFromContainers($containerPath);
        }
    }

    /**
     * @return void
     */
    public function runLoaderRegister(): void
    {
        $this->loadConfigsFromShip();
        $this->loadOnlyShipProviderFromShip();
        $this->loadOnlyVendorProviderFromShip();

        foreach (Nuclear::getAllContainerPaths() as $containerPath) {
            $this->loadEnvFromContainers($containerPath);
            $this->loadConfigsFromContainers($containerPath);
            $this->loadOnlyMainProviderFromContainers($containerPath);
        }
    }
}
