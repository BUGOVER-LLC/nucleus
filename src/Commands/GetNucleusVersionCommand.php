<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Nucleus\Abstracts\Commands\ConsoleCommand;
use Nucleus\Foundation\Nuclear;

class GetNucleusVersionCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus';

    /**
     * The console command description.
     */
    protected $description = 'Display the current Nucleus version.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info(Nuclear::VERSION);
    }
}
