<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;
use Illuminate\Support\Composer;

class DumpAutoload extends Command
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
     */
    public function __construct(private readonly Composer $composer)
    {
        parent::__construct();
    }

    /**
     * Reads all data for the component to be generated (as well as the mappings for path, file and stubs)
     *
     * @return  array|null
     */
    public function handle(): ?array
    {
        $this->info('Composer dump-autoload');
        $this->composer->dumpAutoloads();
        $this->composer->dumpOptimized();

        $this->info('Composer dump-autoload, successful');

        return [];
    }
}
