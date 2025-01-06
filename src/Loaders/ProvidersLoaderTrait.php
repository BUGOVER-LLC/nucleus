<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nucleus\Foundation\Facades\Nuclear;
use Nucleus\Foundation\Nuclear as MainNuclear;

trait ProvidersLoaderTrait
{
    /**
     * Loads only the Main Service Providers from the Containers.
     * All the Service Providers (registered inside the main), will be
     * loaded from the `boot()` function on the parent of the Main
     * Service Providers.
     *
     * @param $containerPath
     */
    public function loadOnlyMainProvidersFromContainers(string $containerPath): void
    {
        $container_providers_directory = $containerPath . '/Provider';
        $this->loadProviders($container_providers_directory);
    }

    /**
     * @param string $directory
     * @return void
     */
    private function loadProviders(string $directory): void
    {
        $main_service_provider_name_start_with = 'Main';

        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $fileExists = $file->isFile() && Str::startsWith(
                    $file->getFilename(),
                    $main_service_provider_name_start_with
                );

                if ($fileExists) {
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
    private function loadProvider(string $provider_full_name): void
    {
        App::register($provider_full_name);
    }

    /**
     * Load the all the registered Service Providers on the Main Service Provider.
     */
    public function loadServiceProviders(): void
    {
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
        $this->loadProvider(MainNuclear::SHIP_NAME . '\Provider\ShipProvider');
    }

    /**
     * @return void
     */
    public function loadOnlyVendorProviderFromShip(): void
    {
        if (File::exists(app_path(MainNuclear::CONTAINERS_DIRECTORY_NAME . '\Vendor\Provider\MainServiceProvider.php'))) {
            $this->loadProvider(MainNuclear::CONTAINERS_DIRECTORY_NAME . '\Vendor\Provider\MainServiceProvider');
        }
    }
}
