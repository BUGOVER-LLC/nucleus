<?php

declare(strict_types=1);

namespace Nucleus\Foundation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function is_array;

class Nuclear
{
    public const string VERSION = '1.9.11';
    public const string SHIP_NAME = 'Ship';
    public const string CONTAINERS_DIRECTORY_NAME = 'Containers';
    public const string SECTION_DIRECTORY_PREFIX = 'Section';

    /**
     * @return array
     */
    public function getShipFoldersNames(): array
    {
        $shipFoldersNames = [];

        foreach ($this->getShipPath() as $shipFoldersPath) {
            $shipFoldersNames[] = basename($shipFoldersPath);
        }

        return $shipFoldersNames;
    }

    /**
     * @return array
     */
    public function getShipPath(): array
    {
        return File::directories(app_path(self::SHIP_NAME));
    }

    /**
     * @param string $sectionName
     * @return array
     */
    public function getSectionContainerNames(string $sectionName): array
    {
        $containerNames = [];
        foreach (File::directories($this->getSectionPath($sectionName)) as $key => $name) {
            $containerNames[] = basename($name);
        }

        return $containerNames;
    }

    /**
     * @param string $sectionName
     * @return string
     */
    private function getSectionPath(string $sectionName): string
    {
        return app_path(self::CONTAINERS_DIRECTORY_NAME . DIRECTORY_SEPARATOR . $sectionName);
    }

    /**
     * Build and return an object of a class from its file path
     *
     * @param string $file_path_name
     *
     * @return  mixed
     */
    public function getClassObjectFromFile(string $file_path_name): mixed
    {
        $class_string = $this->getClassFullNameFromFile($file_path_name);

        return new $class_string();
    }

    /**
     * Get the full name (name \ namespace) of a class from its file path
     * result example: (string) "I\Am\The\Namespace\Of\This\Class"
     *
     * @param string $file_path_name
     *
     * @return  string
     */
    public function getClassFullNameFromFile(string $file_path_name): string
    {
        return "{$this->getClassNamespaceFromFile($file_path_name)}\\{$this->getClassNameFromFile($file_path_name)}";
    }

    /**
     * Get the class namespace form file path using token
     *
     * @param string $file_ath_name
     *
     * @return  null|string
     */
    protected function getClassNamespaceFromFile(string $file_ath_name): ?string
    {
        $src = file_get_contents($file_ath_name);

        $tokens = token_get_all($src);
        $count = count($tokens);
        $i = 0;
        $namespace = '';
        $namespace_ok = false;

        while ($i < $count) {
            $token = $tokens[$i];
            if (is_array($token) && T_NAMESPACE === $token[0]) {
                // Found namespace declaration
                while (++$i < $count) {
                    if (';' === $tokens[$i]) {
                        $namespace_ok = true;
                        $namespace = trim($namespace);

                        break;
                    }
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                }

                break;
            }
            $i++;
        }

        if (!$namespace_ok) {
            return null;
        }

        return $namespace;
    }

    /**
     * Get the class name from file path using token
     *
     * @param string $filePathName
     *
     * @return  mixed
     */
    protected function getClassNameFromFile(string $filePathName): mixed
    {
        $php_code = file_get_contents($filePathName);

        $classes = [];
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if (T_CLASS == $tokens[$i - 2][0] && T_WHITESPACE == $tokens[$i - 1][0] && T_STRING == $tokens[$i][0]) {
                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }

        return $classes[0];
    }

    /**
     * Get the last part of a camel case string.
     * Example input = helloDearWorld | returns = World
     *
     * @param string $className
     *
     * @return  mixed
     */
    public function getClassType(string $className): mixed
    {
        $array = preg_split('/(?=[A-Z])/', $className);

        return end($array);
    }

    /**
     * @return array
     */
    public function getAllContainerNames(): array
    {
        $containersNames = [];

        foreach ($this->getAllContainerPaths() as $containersPath) {
            $containersNames[] = basename($containersPath);
        }

        return $containersNames;
    }

    /**
     * @return array
     */
    public function getAllContainerPaths(): array
    {
        $sectionOrContainerNames = $this->getSectionNames();
        $containerSectionPaths = [];

        foreach ($sectionOrContainerNames as $container_section_name) {
            $contains = Str::contains($container_section_name, self::SECTION_DIRECTORY_PREFIX) && Str::endsWith(
                $container_section_name,
                'Section'
            );

            if ($contains) {
                $sectionContainerPaths = $this->getSectionContainerPaths($container_section_name);
                foreach ($sectionContainerPaths as $containerPath) {
                    $containerSectionPaths[] = $containerPath;
                }
            } else {
                $sectionContainerPaths = $this->getContainerPaths();
                foreach ($sectionContainerPaths as $sectionContainerPath) {
                    if (!Str::endsWith($sectionContainerPath, self::SECTION_DIRECTORY_PREFIX)) {
                        $containerSectionPaths[] = $sectionContainerPath;
                    }
                }
            }
        }

        return $containerSectionPaths;
    }

    /**
     * @return array
     */
    public function getSectionNames(): array
    {
        $section_names = [];

        foreach ($this->getSectionPaths() as $sectionPath) {
            $section_names[] = basename($sectionPath);
        }

        return $section_names;
    }

    /**
     * @return array
     */
    public function getSectionPaths(): array
    {
        return File::directories(app_path(self::CONTAINERS_DIRECTORY_NAME));
    }

    /**
     * @param string $sectionName
     * @return array
     */
    public function getSectionContainerPaths(string $sectionName): array
    {
        return File::directories(app_path(self::CONTAINERS_DIRECTORY_NAME . DIRECTORY_SEPARATOR . $sectionName));
    }

    /**
     * @return array
     */
    public function getContainerPaths(): array
    {
        return File::directories(app_path(self::CONTAINERS_DIRECTORY_NAME . DIRECTORY_SEPARATOR));
    }
}
