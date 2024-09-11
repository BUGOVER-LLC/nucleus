<?php

declare(strict_types=1);

namespace Nucleus\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use JsonException;

/**
 * Class ContainerInstaller
 *
 * @author  Johannes Schobel <johannes.schobel@googlemail.com>
 */
class ContainerInstaller extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package): string
    {
        $container_name = $package->getPrettyName();
        $extras = json_decode(json_encode($package->getExtra(), JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        if (isset($extras->ship->container->name)) {
            $container_name = $extras->ship->container->name;
        }

        return 'app/Containers/Vendor/' . $container_name;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $package_type): bool
    {
        return ('ship-container' === $package_type);
    }
}
