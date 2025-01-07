<?php

declare(strict_types=1);

namespace Nucleus\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use JsonException;

class ContainerInstaller extends LibraryInstaller
{
    private const string CONTAINER_NAME = 'ship-container';

    private const string CONTAINER_PATH = '/Containers/Vendor/';

    /**
     * {@inheritDoc}
     *
     * @throws JsonException
     */
    public function getInstallPath(PackageInterface $package): string
    {
        $containerName = $package->getPrettyName();
        $extras = json_decode(json_encode($package->getExtra(), JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        if (isset($extras->ship->container->name)) {
            $containerName = $extras->ship->container->name;
        }

        $containerPath = app_path() . self::CONTAINER_PATH;

        return $containerPath . $containerName;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $packageType): bool
    {
        return (self::CONTAINER_NAME === $packageType);
    }
}
