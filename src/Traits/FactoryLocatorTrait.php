<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nucleus\Foundation\Nuclear;

trait FactoryLocatorTrait
{
    protected static function newFactory(): ?Factory
    {
        $separator = '\\';
        $containersFactoriesPath = $separator . 'Data' . $separator . 'Factory' . $separator;
        $fullPathSections = explode($separator, static::class);
        $sectionName = $fullPathSections[2];
        $containerName = $fullPathSections[3];
        $nameSpace = ucfirst(
            app_path()
        ) . $separator . Nuclear::CONTAINERS_DIRECTORY_NAME . $separator . $sectionName . $separator . $containerName . $containersFactoriesPath;

        Factory::useNamespace($nameSpace);
        $className = class_basename(static::class);

        if (!class_exists($nameSpace . $className . 'Factory')) {
            return null;
        }

        return Factory::factoryForModel($className);
    }
}
