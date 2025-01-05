<?php

declare(strict_types=1);

namespace Nucleus\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use JsonException;

class ContainerInstaller extends LibraryInstaller
{
    private const string CONTAINER_NAME = 'ship-container';

    private const string CONTAINER_PATH = 'app/Containers/Vendor/';

    /**
     * {@inheritDoc}
     *
     * @throws JsonException
     */
    public function getInstallPath(PackageInterface $package): string
    {
        $container_name = $package->getPrettyName();
        $extras = json_decode(json_encode($package->getExtra(), JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        if (isset($extras->ship->container->name)) {
            $container_name = $extras->ship->container->name;
        }

        return self::CONTAINER_PATH . $container_name;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $package_type): bool
    {
        return (self::CONTAINER_NAME === $package_type);
    }
}
