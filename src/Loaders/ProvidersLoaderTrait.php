<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nucleus\Foundation\Facades\Nuclear;

trait ProvidersLoaderTrait
{
    /**
     * Loads only the Main Service Providers from the Containers.
     * All the Service Providers (registered inside the main), will be
     * loaded from the `boot()` function on the parent of the Main
     * Service Providers.
     * @param $containerPath
     */
    public function loadOnlyMainProvidersFromContainers($containerPath): void
    {
        $container_providers_directory = $containerPath . '/Providers';
        $this->loadProviders($container_providers_directory);
    }

    /**
     * @param $directory
     * @return void
     */
    private function loadProviders($directory): void
    {
        $main_service_provider_name_start_with = 'Main';

        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                // Check if this is the Main Service Provider
                if (File::isFile($file) && Str::startsWith($file->getFilename(), $main_service_provider_name_start_with)) {
                    $serviceProviderClass = Nuclear::getClassFullNameFromFile($file->getPathname());
                    $this->loadProvider($serviceProviderClass);
                }
            }
        }
    }

    /**
     * @param $provider_full_name
     * @return void
     */
    private function loadProvider($provider_full_name): void
    {
        App::register($provider_full_name);
    }

    /**
     * Load the all the registered Service Providers on the Main Service Provider.
     */
    public function loadServiceProviders(): void
    {
        // `$this->serviceProviders` is declared on each Container's Main Service Provider
        foreach ($this->serviceProviders ?? [] as $provider) {
            if (class_exists($provider)) {
                $this->loadProvider($provider);
            }
        }
    }

    /**
     * @return void
     */
    public function loadOnlyShipProviderFromShip(): void
    {
        $this->loadProvider('Ship\Providers\ShipProvider');
    }
}
