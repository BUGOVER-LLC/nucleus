<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;
use Illuminate\Support\Composer;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;

class DumpAutoload extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:dump-autoload';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nucleus call composer dump-autoload';

    /**
     * Create a new command instance.
     *
     * @param Composer $composer
     * @param IlluminateFilesystem $fileSystem
     */
    public function __construct(private readonly Composer $composer, IlluminateFilesystem $fileSystem)
    {
        parent::__construct($fileSystem);
    }

    /**
     * Reads all data for the component to be generated (as well as the mappings for path, file and stubs)
     *
     * @return  array|null
     */
    public function getUserInputs(): ?array
    {
        $this->composer->dumpAutoloads();
        $this->composer->dumpOptimized();

        return [];
    }
}
