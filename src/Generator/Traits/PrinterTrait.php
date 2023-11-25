<?php

declare(strict_types=1);

namespace Nucleus\Generator\Traits;

trait PrinterTrait
{
    /**
     * @param $containerName
     * @param $fileName
     * @return void
     */
    public function printStartedMessage($containerName, $fileName): void
    {
        $this->printInfoMessage('> Generating (' . $fileName . ') in (' . $containerName . ') Container.');
    }

    /**
     * @param $message
     */
    public function printInfoMessage($message): void
    {
        $this->info($message);
    }

    /**
     * @param $type
     *
     * @return void
     */
    public function printFinishedMessage($type): void
    {
        $this->printInfoMessage($type . ' generated successfully.');
    }

    /**
     * @param $message
     */
    public function printErrorMessage($message): void
    {
        $this->error($message);
    }
}
