<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Facades\Nuclear;
use Nucleus\Foundation\Nuclear as MainNuclear;

/**
 * This class is different from other loaders as it is not called by AutoLoaderTrait
 * It is called "database/seeders/DatabaseSeeder.php", Laravel main seeder and only load seeder from
 * Containers (not from "app/Ship/seeders").
 */
trait SeederLoaderTrait
{
    protected string $seedersPath = '/Data/Seeder';

    public function runLoadingSeeders(): void
    {
        $this->loadSeedersFromContainers();
    }

    /**
     * @return void
     */
    private function loadSeedersFromContainers(): void
    {
        $seedersClasses = new Collection();

        $containersDirectories = [];

        foreach (Nuclear::getSectionNames() as $sectionName) {
            foreach (Nuclear::getSectionContainerNames($sectionName) as $containerName) {
                $containersDirectories[] = base_path(
                    config(
                        'app.path'
                    ) . MainNuclear::CONTAINERS_DIRECTORY_NAME . '/' . $sectionName . '/' . $containerName . $this->seedersPath
                );
            }
        }

        $seedersClasses = $this->findSeedersClasses($containersDirectories, $seedersClasses);
        $ordered_seeder_classes = $this->sortSeeders($seedersClasses);

        $this->loadSeeders($ordered_seeder_classes);
    }

    /**
     * @param array $directories
     * @param Collection $seedersClasses
     * @return Collection
     */
    private function findSeedersClasses(array $directories, Collection $seedersClasses): Collection
    {
        foreach ($directories as $directory) {
            if (File::isDirectory($directory)) {
                $files = File::allFiles($directory);

                foreach ($files as $seederClass) {
                    if ($seederClass->isFile()) {
                        $seedersClasses->push(
                            Nuclear::getClassFullNameFromFile(
                                $seederClass->getPathname()
                            )
                        );
                    }
                }
            }
        }

        return $seedersClasses;
    }

    /**
     * @param array|Collection $seedersClasses
     * @return Collection
     */
    private function sortSeeders(array|Collection $seedersClasses): Collection
    {
        $ordered_seeder_classes = new Collection();

        if ($seedersClasses->isEmpty()) {
            return $ordered_seeder_classes;
        }

        foreach ($seedersClasses as $key => $seederFullClassName) {
            // if the class full namespace contain "_" it means it needs to be seeded in order
            if (str_contains($seederFullClassName, '_')) {
                // move all the seeder classes that needs to be seeded in order to their own Collection
                $ordered_seeder_classes->push($seederFullClassName);
                // delete the moved classes from the original collection
                $seedersClasses->forget($key);
            }
        }

        // sort the classes that needed to be ordered
        $ordered_seeder_classes = $ordered_seeder_classes->sortBy(function ($seederFullClassName) {
            // get the order number form the end of each class name
            return substr($seederFullClassName, strpos($seederFullClassName, '_') + 1);
        });

        // append the randomly ordered seeder classes to the end of the ordered seeder classes
        foreach ($seedersClasses as $seederClass) {
            $ordered_seeder_classes->push($seederClass);
        }

        return $ordered_seeder_classes;
    }

    /**
     * @param array|Collection $seedersClasses
     * @return void
     */
    private function loadSeeders(array|Collection $seedersClasses): void
    {
        foreach ($seedersClasses as $seeder) {
            // seed it with call
            $this->call($seeder);
        }
    }
}
