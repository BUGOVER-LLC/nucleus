<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Nucleus\Foundation\Facades\Nuclear;

/**
 * This class is different from other loaders as it is not called by AutoLoaderTrait
 * It is called "database/seeders/DatabaseSeeder.php", Laravel main seeder and only load seeder from
 * Containers (not from "app/Ship/seeders").
 */
trait SeederLoaderTrait
{
    protected string $seedersPath = '/Data/Seeders';

    public function runLoadingSeeders(): void
    {
        $this->loadSeedersFromContainers();
    }

    /**
     * @return void
     */
    private function loadSeedersFromContainers(): void
    {
        $seeders_classes = new Collection();

        $containers_directories = [];

        foreach (Nuclear::getSectionNames() as $section_name) {
            foreach (Nuclear::getSectionContainerNames($section_name) as $container_name) {
                $containers_directories[] = base_path(
                    config('nucleus.path') . 'Containers/' . $section_name . '/' . $container_name . $this->seedersPath
                );
            }
        }

        $seeders_classes = $this->findSeedersClasses($containers_directories, $seeders_classes);
        $ordered_seeder_classes = $this->sortSeeders($seeders_classes);

        $this->loadSeeders($ordered_seeder_classes);
    }

    /**
     * @param array $directories
     * @param Collection $seeders_classes
     * @return mixed
     */
    private function findSeedersClasses(array $directories, Collection $seeders_classes): mixed
    {
        foreach ($directories as $directory) {
            if (File::isDirectory($directory)) {
                $files = File::allFiles($directory);

                foreach ($files as $seeder_class) {
                    if (File::isFile($seeder_class)) {
                        // do not seed the classes now, just store them in a collection and w
                        $seeders_classes->push(
                            Nuclear::getClassFullNameFromFile(
                                $seeder_class->getPathname()
                            )
                        );
                    }
                }
            }
        }

        return $seeders_classes;
    }

    /**
     * @param $seeders_classes
     * @return Collection
     */
    private function sortSeeders($seeders_classes): Collection
    {
        $ordered_seeder_classes = new Collection();

        if ($seeders_classes->isEmpty()) {
            return $ordered_seeder_classes;
        }

        foreach ($seeders_classes as $key => $seederFullClassName) {
            // if the class full namespace contain "_" it means it needs to be seeded in order
            if (str_contains($seederFullClassName, '_')) {
                // move all the seeder classes that needs to be seeded in order to their own Collection
                $ordered_seeder_classes->push($seederFullClassName);
                // delete the moved classes from the original collection
                $seeders_classes->forget($key);
            }
        }

        // sort the classes that needed to be ordered
        $ordered_seeder_classes = $ordered_seeder_classes->sortBy(function ($seeder_full_class_name) {
            // get the order number form the end of each class name
            return substr($seeder_full_class_name, strpos($seeder_full_class_name, '_') + 1);
        });

        // append the randomly ordered seeder classes to the end of the ordered seeder classes
        foreach ($seeders_classes as $seederClass) {
            $ordered_seeder_classes->push($seederClass);
        }

        return $ordered_seeder_classes;
    }

    /**
     * @param $seedersClasses
     */
    private function loadSeeders($seedersClasses): void
    {
        foreach ($seedersClasses as $seeder) {
            // seed it with call
            $this->call($seeder);
        }
    }
}
