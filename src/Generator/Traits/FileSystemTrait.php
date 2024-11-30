<?php

declare(strict_types=1);

namespace Nucleus\Generator\Traits;

use Exception;

trait FileSystemTrait
{
    /**
     * @param $filePath
     * @param $stubContent
     *
     * @return bool|int
     */
    public function generateFile($filePath, $stubContent): bool|int
    {
        return $this->fileSystem->put($filePath, $stubContent);
    }

    /**
     * If path is for a directory, create it otherwise do nothing
     *
     * @param $path
     */
    public function createDirectory($path): void
    {
        if ($this->alreadyExists($path)) {
            $this->printErrorMessage($this->fileType . ' already exists');

            // the file does exist - return but NOT exit
            return;
        }

        try {
            if (!$this->fileSystem->isDirectory(\dirname($path))) {
                $this->fileSystem->makeDirectory(\dirname($path), 0777, true, true);
            }
        } catch (Exception $e) {
            $this->printErrorMessage('Could not create ' . $path);
        }
    }

    /**
     * Determine if the file already exists.
     *
     * @param $path
     *
     * @return bool
     */
    protected function alreadyExists($path): bool
    {
        return $this->fileSystem->exists($path);
    }
}
